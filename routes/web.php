<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ObatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\FarmasiController;

Route::middleware(['auth'])->group(function () {
    Route::resource('tagihan', TagihanController::class);
    Route::post('/tagihan/{tagihan}/pembayaran', [PembayaranController::class, 'store'])
    ->name('tagihan.pembayaran.store');
});
use App\Http\Controllers\PoliController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalDokterController;

// Perhatikan namespace tambahan \Dokter di bawah ini:
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\DashboardDokterController;

// ... (kode route lainnya biarkan seperti biasa)

// Route untuk Dokter (Pelayanan & Pemeriksaan Pasien)
Route::middleware(['auth', 'active', 'role:dokter'])->prefix('dokter')->name('dokter.')->group(function () {
    Route::get('/dashboard', [DashboardDokterController::class, 'index'])->name('dashboard');
    
    // Route Modul Rekam Medis
    Route::get('/pemeriksaan', [PemeriksaanController::class, 'index'])->name('pemeriksaan.index');
    Route::get('/pemeriksaan/create/{pendaftaran_id}', [PemeriksaanController::class, 'create'])->name('pemeriksaan.create');
    Route::post('/pemeriksaan', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
    Route::get('/pemeriksaan/{id}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show');
});

// Halaman utama
Route::view('/', 'home')->name('home');

// Login dan registrasi untuk pengguna yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});

// Fitur pengguna yang sudah login dan aktif
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

// Fitur khusus admin
Route::middleware(['auth', 'active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('pasien', PasienController::class)
            ->except(['destroy']);
    });

// Fitur obat untuk admin dan farmasi
Route::middleware(['auth', 'active', 'role:admin,farmasi'])
    ->prefix('farmasi')
    ->name('farmasi.')
    ->group(function () {
        Route::resource('obat', ObatController::class);
    });

// Fitur Pendaftaran
Route::middleware(['auth', 'active', 'role:pasien'])
    ->prefix('pasien')
    ->name('pasien.')
    ->group(function () {
        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])
            ->name('pendaftaran.index');

        Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])
            ->name('pendaftaran.create');

        Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])
            ->whereNumber('pendaftaran')
            ->name('pendaftaran.show');

        Route::post(
            '/pendaftaran',
            [PendaftaranController::class, 'store']
        )->name('pendaftaran.store');
    });

// Fitur Farmasi & Resep (Dipisah di luar grup pasien)
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/farmasi', [FarmasiController::class, 'index'])->name('farmasi.index');
    Route::put('/farmasi/{id}/status', [FarmasiController::class, 'updateStatus'])->name('farmasi.updateStatus');
});
