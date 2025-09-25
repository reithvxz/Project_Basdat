<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\TemplateController;
use App\Models\Mahasiswa;

// Halaman utama, redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute untuk Mahasiswa (Guard Mahasiswa)
Route::middleware('auth:mahasiswa')->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/status', [SuratController::class, 'index'])->name('status');
    Route::get('/pengajuan', [SuratController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [SuratController::class, 'store'])->name('pengajuan.store');
    Route::delete('/surat/{surat}/batal', [SuratController::class, 'destroy'])->name('surat.batal');
    Route::get('/surat/{surat}/tracking', [SuratController::class, 'tracking'])->name('surat.tracking');
    Route::get('/template', [TemplateController::class, 'index'])->name('template.index');
    Route::get('/ajax/template-link', [TemplateController::class, 'getLink'])->name('ajax.template.link');
});

// Rute untuk Admin (Guard Web/Users)
Route::middleware('auth:web')->group(function() {
    Route::get('/surat-masuk', [ApprovalController::class, 'index'])->name('surat.masuk');
    Route::get('/surat/{surat}/periksa', [ApprovalController::class, 'show'])->name('surat.periksa');
    Route::post('/surat/{surat}/approve', [ApprovalController::class, 'approve'])->name('surat.approve');
    Route::post('/surat/{surat}/reject', [ApprovalController::class, 'reject'])->name('surat.reject');
});

// Rute umum yang bisa diakses kedua guard
Route::middleware('auth:web,mahasiswa')->group(function(){
    // Filepath di-encode agar tidak ada karakter aneh di URL
    Route::get('/preview/{filepath}', [ApprovalController::class, 'preview'])->name('file.preview');
});


// Rute autentikasi bawaan Breeze
require __DIR__.'/auth.php';

// TAMBAHKAN RUTE INI DI PALING BAWAH
Route::get('/reset-password-test', function() {
    $mahasiswa = Mahasiswa::where('nim', '164231088')->first();
    if ($mahasiswa) {
        $mahasiswa->password = Hash::make('coba123');
        $mahasiswa->save();
        return 'Password untuk NIM 164231088 telah di-reset ke: <b>coba123</b>';
    }
    return 'Mahasiswa dengan NIM 164231088 tidak ditemukan.';
});