<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Tanaman;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('laporan.index', [
            'from' => $data['from'],
            'to' => $data['to'],
            'jadwalTanam' => $data['jadwalTanam'],
            'estimasiPanen' => $data['estimasiPanen'],
            'riwayatNotifikasi' => $data['riwayatNotifikasi'],
        ]);
    }

    public function pdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $options = new Options;
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $html = view('laporan.pdf', [
            'from' => $data['from'],
            'to' => $data['to'],
            'jadwalTanam' => $data['jadwalTanam'],
            'estimasiPanen' => $data['estimasiPanen'],
            'riwayatNotifikasi' => $data['riwayatNotifikasi'],
            'printedAt' => now()->format('Y-m-d H:i'),
            'printedBy' => Auth::user()->name,
        ])->render();

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'laporan-tanam-pepaya-'.$data['from'].'-sd-'.$data['to'].'.pdf';

        return response()->streamDownload(function () use ($dompdf) {
            echo $dompdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function buildReportData(Request $request): array
    {
        $user = Auth::user();

        $from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::today()->startOfMonth();
        $to = $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : Carbon::today()->endOfMonth();

        $tanamanBase = Tanaman::query()->with('user');
        $notifikasiBase = Notifikasi::query()->with(['tanaman', 'jadwalPerawatan']);

        if ($user->role !== 'admin') {
            $tanamanBase->where('user_id', $user->id);
            $notifikasiBase->whereHas('tanaman', fn ($t) => $t->where('user_id', $user->id));
        }

        $jadwalTanam = (clone $tanamanBase)
            ->whereBetween('tanggal_tanam', [$from->toDateString(), $to->toDateString()])
            ->orderBy('tanggal_tanam')
            ->get();

        $estimasiPanen = (clone $tanamanBase)
            ->whereBetween('estimasi_panen', [$from->toDateString(), $to->toDateString()])
            ->orderBy('estimasi_panen')
            ->get();

        $riwayatNotifikasi = (clone $notifikasiBase)
            ->whereBetween('tanggal_kirim', [$from->toDateTimeString(), $to->toDateTimeString()])
            ->orderByDesc('tanggal_kirim')
            ->get();

        return [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'jadwalTanam' => $jadwalTanam,
            'estimasiPanen' => $estimasiPanen,
            'riwayatNotifikasi' => $riwayatNotifikasi,
        ];
    }
}
