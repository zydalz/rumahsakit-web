<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

Route::middleware(['auth', 'active'])->group(function () {
    // Route dashboard dan logout
});

// Contoh pembatasan route untuk modul yang akan datang.
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {
        // Route pengelolaan akun staf akan ditambahkan
        // ketika modul admin sudah disepakati.
    });

    use App\Http\Controllers\ObatController;

Route::middleware(['auth'])->group(function () {
    Route::resource('obat', ObatController::class);
});
