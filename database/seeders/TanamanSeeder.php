<?php

namespace Database\Seeders;

use App\Models\JadwalPerawatan;
use App\Models\Tanaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TanamanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@gmail.com')->first();
        $user = User::query()->where('email', 'user@gmail.com')->first();

        $ownerIds = array_values(array_filter([
            $admin?->id,
            $user?->id,
        ]));

        if (empty($ownerIds)) {
            return;
        }

        $today = Carbon::today();

        $samples = [
            ['lokasi' => 'Blok A - Kebun Utara', 'luas' => 1200.50, 'days_ago' => 20, 'status' => 'aktif'],
            ['lokasi' => 'Blok B - Kebun Timur', 'luas' => 850.00, 'days_ago' => 55, 'status' => 'aktif'],
            ['lokasi' => 'Blok C - Kebun Selatan', 'luas' => 640.75, 'days_ago' => 95, 'status' => 'aktif'],
            ['lokasi' => 'Blok D - Kebun Barat', 'luas' => 430.00, 'days_ago' => 150, 'status' => 'aktif'],
            ['lokasi' => 'Blok E - Kebun Tengah', 'luas' => 990.25, 'days_ago' => 190, 'status' => 'panen'],
            ['lokasi' => 'Blok F - Kebun Lereng', 'luas' => 510.00, 'days_ago' => 250, 'status' => 'selesai'],
        ];

        DB::transaction(function () use ($ownerIds, $samples, $today) {
            foreach ($samples as $idx => $s) {
                $ownerId = $ownerIds[$idx % count($ownerIds)];

                $tanggalBibit = $today->copy()->subDays((int) $s['days_ago'])->startOfDay();
                $tanggalPindahLahan = $tanggalBibit->copy()->addMonthNoOverflow();
                $estimasiPanen = $tanggalPindahLahan->copy()->addMonthsNoOverflow(6);
                $panenMulai = $estimasiPanen->copy();
                $panenSampai = $panenMulai->copy()->addYearsNoOverflow(3);
                $panenMode = $idx % 2 === 0 ? 'normal' : 'tidak_normal';

                $tanaman = Tanaman::query()->create([
                    'user_id' => $ownerId,
                    'nama_tanaman' => 'Pepaya',
                    'tanggal_tanam' => $tanggalBibit->toDateString(),
                    'tanggal_pindah_lahan' => $tanggalPindahLahan->toDateString(),
                    'estimasi_panen' => $estimasiPanen->toDateString(),
                    'panen_mulai' => $panenMulai->toDateString(),
                    'panen_sampai' => $panenSampai->toDateString(),
                    'luas_lahan' => $s['luas'],
                    'lokasi' => $s['lokasi'],
                    'keterangan' => 'Data contoh seeder tanaman pepaya.',
                    'status' => $s['status'],
                    'panen_mode' => $panenMode,
                ]);

                $this->generateJadwalPerawatan($tanaman, $tanggalPindahLahan, $panenMulai, $panenSampai);
            }
        });
    }

    private function generateJadwalPerawatan(Tanaman $tanaman, Carbon $tanggalPindahLahan, Carbon $panenMulai, Carbon $panenSampai): void
    {
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
