<?php

namespace App\Filament\Resources\PengajuanJadwalResource\Pages;

use App\Filament\Resources\PengajuanJadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log; // <-- Tambahkan Log jika belum ada
use Illuminate\Validation\ValidationException; 
use App\Models\User;
use App\Notifications\PengajuanBaruUntukAdmin;
use Illuminate\Support\Facades\Notification;// <-- Tambahkan jika ingin validasi di sini

class CreatePengajuanJadwal extends CreateRecord
{
    protected static string $resource = PengajuanJadwalResource::class;

    // Arahkan kembali ke halaman index setelah berhasil create
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Ubah judul halaman
     protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pengajuan Jadwal Terkirim';
    }

    protected function afterCreate(): void
    {
        // Ambil data pengajuan yang baru saja dibuat
        $pengajuan = $this->record; 

        // Ambil semua user admin
        $admins = User::where('role', 'admin')->get();

        // Kirim notifikasi ke semua admin
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PengajuanBaruUntukAdmin($pengajuan));
             Log::info("Notifikasi pengajuan baru ID {$pengajuan->id} dikirim ke admin.");
        } else {
             Log::warning("Tidak ada admin ditemukan untuk notifikasi pengajuan baru ID {$pengajuan->id}.");
        }
    }
  
    // ----------------------------------------
}