<?php

namespace App\Console\Commands;

use App\Models\JadwalPerawatan;
use App\Models\Notifikasi;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPerawatanNotifications extends Command
{
    protected $signature = 'wa:send-perawatan {--date=}';

    protected $description = 'Kirim notifikasi WhatsApp berdasarkan jadwal perawatan (simulasi).';

    public function handle(WhatsAppService $whatsApp): int
    {
        $dateOption = $this->option('date');
        $date = $dateOption ? Carbon::parse($dateOption)->toDateString() : Carbon::today()->toDateString();

        $jadwal = JadwalPerawatan::query()
            ->where('status', 'pending')
            ->where('jenis', '!=', 'panen')
            ->whereDate('tanggal', $date)
            ->with(['tanaman.user'])
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($jadwal as $item) {
            $tanaman = $item->tanaman;
            $user = $tanaman?->user;
            $nomor = $user?->phone;

            $jenisText = match ($item->jenis) {
                'pindah_lahan' => 'pindah lahan (setelah pembibitan 1 bulan)',
                'pupuk_kandang' => 'pemupukan pupuk kandang',
                'pupuk_kimia' => 'pemupukan pupuk kimia',
                'semprot_hama' => 'penyemprotan hama',
                'semprot_rumput' => 'semprot rumput bedeng',
                'timbun_bedeng' => 'timbun bedeng',
                default => 'perawatan tanaman',
            };
            $pesan = "Halo, ini pengingat perawatan tanaman pepaya.\nHari ini jadwal {$jenisText}.";

            if (! $nomor) {
                $item->update([
                    'status' => 'gagal',
                    'sent_at' => now(),
                ]);

                Notifikasi::create([
                    'tanaman_id' => $tanaman?->id,
                    'jadwal_perawatan_id' => $item->id,
                    'nomor' => '-',
                    'pesan' => $pesan,
                    'tanggal_kirim' => now(),
                    'status' => 'gagal',
                    'response' => 'Nomor WhatsApp belum diisi pada profil user.',
                ]);

                $failed++;

                continue;
            }

            $result = $whatsApp->send($nomor, $pesan);

            $item->update([
                'status' => $result['status'],
                'sent_at' => now(),
            ]);

            Notifikasi::create([
                'tanaman_id' => $tanaman->id,
                'jadwal_perawatan_id' => $item->id,
                'nomor' => $nomor,
                'pesan' => $pesan,
                'tanggal_kirim' => now(),
                'status' => $result['status'],
                'response' => $result['response'] ?? null,
            ]);

            if ($result['success']) {
                $sent++;
            } else {
                $failed++;
            }
        }

        $this->info("Tanggal: {$date} | Terkirim: {$sent} | Gagal: {$failed}");

        return self::SUCCESS;
    }
}
