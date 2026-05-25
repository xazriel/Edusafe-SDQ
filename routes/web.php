<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SdqController;
use App\Http\Controllers\DashboardController;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Auth (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Siswa
Route::middleware('auth')->group(function () {
    Route::get('/sdq', [SdqController::class, 'index']);
    Route::post('/sdq/submit', [SdqController::class, 'submit']);
    Route::get('/dashboard-siswa', [DashboardController::class, 'siswa']);
});

// Guru BK
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/riwayat', [DashboardController::class, 'riwayat']);
    Route::get('/riwayat/{id}', [DashboardController::class, 'detail']);
});