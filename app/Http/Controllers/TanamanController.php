<?php

namespace App\Http\Controllers;

use App\Models\JadwalPerawatan;
use App\Models\Tanaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TanamanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $q = request()->query('q');

        $tanamanBase = Tanaman::query()
            ->when($user->role !== 'admin', fn ($qb) => $qb->where('user_id', $user->id))
            ->when($q, function ($qb) use ($q, $user) {
                $qb->where(function ($w) use ($q, $user) {
                    $w->where('nama_tanaman', 'like', '%'.$q.'%')
                        ->orWhere('lokasi', 'like', '%'.$q.'%')
                        ->orWhere('keterangan', 'like', '%'.$q.'%');

                    if ($user->role === 'admin') {
                        $w->orWhereHas('user', fn ($u) => $u->where('name', 'like', '%'.$q.'%')->orWhere('email', 'like', '%'.$q.'%'));
                    }
                });
            });

        $tanaman = (clone $tanamanBase)
            ->with('user')
            ->withCount([
                'jadwalPerawatan as jadwal_hari_ini' => fn ($q) => $q->whereDate('tanggal', Carbon::today()->toDateString()),
            ])
            ->orderByDesc('id')
            ->paginate(10)
            ->appends(['q' => $q]);

        $totalTanaman = (clone $tanamanBase)->count();
        $tanamanAktif = (clone $tanamanBase)->where('status', 'aktif')->count();
        $jadwalHariIni = JadwalPerawatan::query()
            ->where('status', 'pending')
            ->where('jenis', '!=', 'panen')
            ->whereDate('tanggal', Carbon::today()->toDateString())
            ->when($user->role !== 'admin', function ($qb) use ($user) {
                $qb->whereHas('tanaman', fn ($t) => $t->where('user_id', $user->id));
            })
            ->count();
        $panen30Hari = (clone $tanamanBase)
            ->whereBetween('estimasi_panen', [Carbon::today()->toDateString(), Carbon::today()->addDays(30)->toDateString()])
            ->count();

        $users = $user->role === 'admin'
            ? User::query()->select('id', 'name', 'email')->orderBy('name')->get()
            : collect();

        return view('tanaman.index', [
            'tanaman' => $tanaman,
            'users' => $users,
            'q' => $q,
            'totalTanaman' => $totalTanaman,
            'tanamanAktif' => $tanamanAktif,
            'jadwalHariIni' => $jadwalHariIni,
            'panen30Hari' => $panen30Hari,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'nama_tanaman' => ['nullable', 'string', 'max:255'],
            'tanggal_tanam' => ['required', 'date'],
            'panen_mode' => ['nullable', 'in:normal,tidak_normal'],
            'luas_lahan' => ['required', 'numeric', 'min:0'],
            'lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $ownerId = $user->role === 'admin' && ! empty($validated['user_id']) ? (int) $validated['user_id'] : $user->id;
        $tanggalBibit = Carbon::parse($validated['tanggal_tanam'])->startOfDay();
        $tanggalPindahLahan = $tanggalBibit->copy()->addMonthNoOverflow();
        $estimasiPanen = $tanggalPindahLahan->copy()->addMonthsNoOverflow(6);
        $panenMulai = $estimasiPanen->copy();
        $panenSampai = $panenMulai->copy()->addYearsNoOverflow(3);
        $panenMode = $validated['panen_mode'] ?? 'normal';

        DB::transaction(function () use ($validated, $ownerId, $tanggalBibit, $tanggalPindahLahan, $estimasiPanen, $panenMulai, $panenSampai, $panenMode) {
            $tanaman = Tanaman::create([
                'user_id' => $ownerId,
                'nama_tanaman' => $validated['nama_tanaman'] ?: 'Pepaya',
                'tanggal_tanam' => $tanggalBibit->toDateString(),
                'tanggal_pindah_lahan' => $tanggalPindahLahan->toDateString(),
                'estimasi_panen' => $estimasiPanen->toDateString(),
                'panen_mulai' => $panenMulai->toDateString(),
                'panen_sampai' => $panenSampai->toDateString(),
                'luas_lahan' => $validated['luas_lahan'],
                'lokasi' => $validated['lokasi'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'aktif',
                'panen_mode' => $panenMode,
            ]);

            $this->generateJadwalPerawatan($tanaman, $tanggalPindahLahan, $panenMulai, $panenSampai);
        });

        return redirect()->route('tanaman.index')->with('success', 'Data tanaman berhasil ditambahkan.');
    }

    public function update(Request $request, Tanaman $tanaman)
    {
        $user = Auth::user();
        $this->authorizeTanaman($tanaman, $user);

        $validated = $request->validate([
            'nama_tanaman' => ['nullable', 'string', 'max:255'],
            'tanggal_tanam' => ['required', 'date'],
            'panen_mode' => ['nullable', 'in:normal,tidak_normal'],
            'luas_lahan' => ['required', 'numeric', 'min:0'],
            'lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $tanggalBibit = Carbon::parse($validated['tanggal_tanam'])->startOfDay();
        $tanggalPindahLahan = $tanggalBibit->copy()->addMonthNoOverflow();
        $estimasiPanen = $tanggalPindahLahan->copy()->addMonthsNoOverflow(6);
        $panenMulai = $estimasiPanen->copy();
        $panenSampai = $panenMulai->copy()->addYearsNoOverflow(3);
        $panenMode = $validated['panen_mode'] ?? ($tanaman->panen_mode ?: 'normal');

        DB::transaction(function () use ($tanaman, $validated, $tanggalBibit, $tanggalPindahLahan, $estimasiPanen, $panenMulai, $panenSampai, $panenMode) {
            $tanaman->update([
                'nama_tanaman' => $validated['nama_tanaman'] ?: 'Pepaya',
                'tanggal_tanam' => $tanggalBibit->toDateString(),
                'tanggal_pindah_lahan' => $tanggalPindahLahan->toDateString(),
                'estimasi_panen' => $estimasiPanen->toDateString(),
                'panen_mulai' => $panenMulai->toDateString(),
                'panen_sampai' => $panenSampai->toDateString(),
                'luas_lahan' => $validated['luas_lahan'],
                'lokasi' => $validated['lokasi'],
                'keterangan' => $validated['keterangan'] ?? null,
                'panen_mode' => $panenMode,
            ]);

            $this->generateJadwalPerawatan($tanaman, $tanggalPindahLahan, $panenMulai, $panenSampai, true);
        });

        return redirect()->route('tanaman.index')->with('success', 'Data tanaman berhasil diperbarui.');
    }

    public function destroy(Tanaman $tanaman)
    {
        $user = Auth::user();
        $this->authorizeTanaman($tanaman, $user);

        $tanaman->delete();

        return redirect()->route('tanaman.index')->with('success', 'Data tanaman berhasil dihapus.');
    }

    public function updateStatus(Request $request, Tanaman $tanaman)
    {
        $user = Auth::user();
        $this->authorizeTanaman($tanaman, $user);

        $validated = $request->validate([
            'status' => ['required', 'in:aktif,panen,selesai'],
        ]);

        $tanaman->update(['status' => $validated['status']]);

        return redirect()->route('tanaman.index')->with('success', 'Status tanaman berhasil diperbarui.');
    }

    public function jadwal(Tanaman $tanaman)
    {
        $user = Auth::user();
        $this->authorizeTanaman($tanaman, $user);

        $tanaman->load(['jadwalPerawatan' => fn ($q) => $q->orderBy('tanggal')]);

        return view('tanaman._jadwal', ['tanaman' => $tanaman]);
    }

    private function authorizeTanaman(Tanaman $tanaman, $user): void
    {
        if ($user->role !== 'admin' && (int) $tanaman->user_id !== (int) $user->id) {
            abort(403);
        }
    }

    private function generateJadwalPerawatan(Tanaman $tanaman, Carbon $tanggalPindahLahan, Carbon $panenMulai, Carbon $panenSampai, bool $replace = false): void
    {
        if ($replace) {
            JadwalPerawatan::query()->where('tanaman_id', $tanaman->id)->delete();
        }

        $rows = [];
        $now = now();

        $rows[] = [
            'tanaman_id' => $tanaman->id,
            'jenis' => 'pindah_lahan',
            'tanggal' => $tanggalPindahLahan->toDateString(),
            'status' => 'pending',
            'sent_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $endPerawatan = $panenSampai->copy();

        $pupukKandang = $tanggalPindahLahan->copy()->addDays(14);
        while ($pupukKandang->lte($endPerawatan)) {
            $rows[] = [
                'tanaman_id' => $tanaman->id,
                'jenis' => 'pupuk_kandang',
                'tanggal' => $pupukKandang->toDateString(),
                'status' => 'pending',
                'sent_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $pupukKandang->addDays(14);
        }

        $pupukKimia = $tanggalPindahLahan->copy()->addDays(14);
        while ($pupukKimia->lte($endPerawatan)) {
            $rows[] = [
                'tanaman_id' => $tanaman->id,
                'jenis' => 'pupuk_kimia',
                'tanggal' => $pupukKimia->toDateString(),
                'status' => 'pending',
                'sent_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $pupukKimia->addDays(14);
        }

        $semprotHama = $tanggalPindahLahan->copy()->addMonthNoOverflow();
        while ($semprotHama->lte($endPerawatan)) {
            $rows[] = [
                'tanaman_id' => $tanaman->id,
                'jenis' => 'semprot_hama',
                'tanggal' => $semprotHama->toDateString(),
                'status' => 'pending',
                'sent_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $semprotHama->addMonthNoOverflow();
        }

        $semprotRumput = $tanggalPindahLahan->copy()->addMonthsNoOverflow(2);
        while ($semprotRumput->lte($endPerawatan)) {
            $rows[] = [
                'tanaman_id' => $tanaman->id,
                'jenis' => 'semprot_rumput',
                'tanggal' => $semprotRumput->toDateString(),
                'status' => 'pending',
                'sent_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $semprotRumput->addMonthsNoOverflow(2);
        }

        $timbunBedeng = $tanggalPindahLahan->copy()->addMonthsNoOverflow(3);
        while ($timbunBedeng->lte($endPerawatan)) {
            $rows[] = [
                'tanaman_id' => $tanaman->id,
                'jenis' => 'timbun_bedeng',
                'tanggal' => $timbunBedeng->toDateString(),
                'status' => 'pending',
                'sent_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $timbunBedeng->addMonthsNoOverflow(3);
        }

        $panenStart = $panenMulai->copy();
        $panenEnd = $panenSampai->copy();

        if ($tanaman->panen_mode === 'tidak_normal') {
            $panen = $panenStart->copy();
            while ($panen->lte($panenEnd)) {
                $rows[] = [
                    'tanaman_id' => $tanaman->id,
                    'jenis' => 'panen',
                    'tanggal' => $panen->toDateString(),
                    'status' => 'pending',
                    'sent_at' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $panen->addDays(4);
            }
        } else {
            $panen = $panenStart->copy();
            while ($panen->lte($panenEnd)) {
                if (in_array($panen->dayOfWeekIso, [1, 3, 5], true)) {
                    $rows[] = [
                        'tanaman_id' => $tanaman->id,
                        'jenis' => 'panen',
                        'tanggal' => $panen->toDateString(),
                        'status' => 'pending',
                        'sent_at' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                $panen->addDay();
            }
        }

        if ($rows) {
            JadwalPerawatan::query()->insert($rows);
        }
    }
}
