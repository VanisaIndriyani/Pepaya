<?php

namespace App\Http\Controllers;

use App\Models\JadwalPerawatan;
use App\Models\Tanaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tanamanBase = Tanaman::query();
        if ($user->role !== 'admin') {
            $tanamanBase->where('user_id', $user->id);
        }

        $today = Carbon::today();
        $next7 = $today->copy()->addDays(7);
        $next30 = $today->copy()->addDays(30);

        $totalTanaman = (clone $tanamanBase)->count();
        $tanamanAktif = (clone $tanamanBase)->where('status', 'aktif')->count();
        $estimasiPanen = (clone $tanamanBase)->whereBetween('estimasi_panen', [$today->toDateString(), $next30->toDateString()])->count();

        $jadwalTanam = JadwalPerawatan::query()
            ->where('status', 'pending')
            ->where('jenis', '!=', 'panen')
            ->whereBetween('tanggal', [$today->toDateString(), $next7->toDateString()])
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->whereHas('tanaman', fn ($t) => $t->where('user_id', $user->id));
            })
            ->count();

        $panenTerdekat = (clone $tanamanBase)
            ->where('status', 'aktif')
            ->orderBy('estimasi_panen')
            ->first();

        $labels = [];
        $series = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonthsNoOverflow($i)->startOfMonth();
            $labels[] = $month->translatedFormat('M Y');

            $series[] = (clone $tanamanBase)
                ->whereBetween('tanggal_tanam', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
                ->count();
        }

        $tanamanTerbaru = (clone $tanamanBase)
            ->latest('id')
            ->take(6)
            ->get();

        return view('dashboard.index', [
            'totalTanaman' => $totalTanaman,
            'tanamanAktif' => $tanamanAktif,
            'jadwalTanam' => $jadwalTanam,
            'estimasiPanen' => $estimasiPanen,
            'chartLabels' => $labels,
            'chartSeries' => $series,
            'panenTerdekat' => $panenTerdekat,
            'tanamanTerbaru' => $tanamanTerbaru,
        ]);
    }
}
