<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TanamanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/tanaman', [TanamanController::class, 'index'])->name('tanaman.index');
    Route::post('/tanaman', [TanamanController::class, 'store'])->name('tanaman.store');
    Route::put('/tanaman/{tanaman}', [TanamanController::class, 'update'])->name('tanaman.update');
    Route::delete('/tanaman/{tanaman}', [TanamanController::class, 'destroy'])->name('tanaman.destroy');
    Route::put('/tanaman/{tanaman}/status', [TanamanController::class, 'updateStatus'])->name('tanaman.status');
    Route::get('/tanaman/{tanaman}/jadwal', [TanamanController::class, 'jadwal'])->name('tanaman.jadwal');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/kirim-hari-ini', [NotifikasiController::class, 'kirimHariIni'])->name('notifikasi.kirimHariIni');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});
