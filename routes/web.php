<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JadwalPublikController;
use App\Http\Controllers\Dosen\PengajuanJadwalController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\PushSubscriptionController; // Pastikan import ini ada
use App\Http\Controllers\GeminiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute yang sudah ada
Route::get('/', [JadwalPublikController::class, 'index'])->name('welcome');
Route::get('/jadwal-laboratorium', [JadwalPublikController::class, 'showFullSchedule'])->name('jadwal.full');
Route::post('/gemini/ask', [GeminiController::class, 'ask'])->name('gemini.ask');




Route::middleware(['auth'])->group(function () {

    // --- Rute untuk Dosen ---
    Route::middleware(['role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/jadwal/ajukan', [PengajuanJadwalController::class, 'create'])->name('jadwal.create');
        Route::post('/jadwal/ajukan', [PengajuanJadwalController::class, 'store'])->name('jadwal.store');
        // Rute push.subscribe SUDAH DIPINDAHKAN dari sini
    });

    // --- Rute untuk Admin ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pengajuan', [ApprovalController::class, 'index'])->name('pengajuan.index');
        Route::post('/pengajuan/setujui/{pengajuan}', [ApprovalController::class, 'setujui'])->name('pengajuan.setujui');
        Route::post('/pengajuan/tolak/{pengajuan}', [ApprovalController::class, 'tolak'])->name('pengajuan.tolak');
    });

    // --- Rute untuk Menyimpan Push Subscription (Untuk user yg login, misal: maheswa) ---
    // PINDAHKAN KE SINI: Di dalam 'auth', tapi di luar 'role:dosen' atau 'role:admin'
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');

   

});

// Rute fallback atau rute auth lainnya mungkin ada di sini
// require __DIR__.'/auth.php'; // Jika Anda pakai Breeze/Jetstream