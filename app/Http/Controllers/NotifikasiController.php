<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $notifikasi = Notifikasi::query()
            ->with(['tanaman', 'jadwalPerawatan'])
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->whereHas('tanaman', fn ($t) => $t->where('user_id', $user->id));
            })
            ->latest('tanggal_kirim')
            ->paginate(15);

        return view('notifikasi.index', [
            'notifikasi' => $notifikasi,
        ]);
    }

    public function kirimHariIni()
    {
        Artisan::call('wa:send-perawatan');

        return redirect()->route('notifikasi.index')->with('success', 'Proses kirim notifikasi hari ini sudah dijalankan (simulasi).');
    }
}
