<?php
// app/Notifications/PengajuanBaruUntukAdmin.php

namespace App\Notifications;

use App\Models\PengajuanJadwal;
// --- TAMBAHKAN IMPORT RESOURCE ---
use App\Filament\Resources\PengajuanJadwalResource;
// ---------------------------------
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon; // Pastikan Carbon di-import jika dipakai
use App\Models\TimeSlot;

class PengajuanBaruUntukAdmin extends Notification
{
    use Queueable;

    // Properti untuk menyimpan data pengajuan
    public PengajuanJadwal $pengajuan;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(PengajuanJadwal $pengajuan) // Terima objek PengajuanJadwal
    {
        // Simpan objek ke properti class
        $this->pengajuan = $pengajuan;
    }

    /**
     * Tentukan channel notifikasi (database untuk lonceng Filament).
     */
    public function via(object $notifiable): array
    {
        // Gunakan '$this->pengajuan' jika perlu akses data di sini (tapi biasanya tidak)
        return ['database'];
    }

    /**
     * Format data untuk disimpan di database.
     */
    public function toDatabase(object $notifiable): array
    {
        // --- GUNAKAN $this->pengajuan UNTUK MENGAKSES DATA ---
        // Eager load relasi jika belum (lebih baik dilakukan sebelum kirim notif)
        $this->pengajuan->loadMissing(['dosen', 'room']); 
        
        $dosen = $this->pengajuan->dosen->name ?? 'N/A';
        $mk = $this->pengajuan->mata_kuliah ?? 'N/A';
        $ruang = $this->pengajuan->room->nama_ruang ?? 'N/A';
        $alasan = $this->pengajuan->alasan_dosen ?? '-'; // Ambil alasan
        
        // Buat URL ke halaman detail atau index approval
        // Jika ingin ke detail, perlu ID record, tapi index lebih umum
        $url = PengajuanJadwalResource::getUrl('index'); 
        // $url = PengajuanJadwalResource::getUrl('view', ['record' => $this->pengajuan->id]); // Alternatif ke detail view

        return [
            'title' => 'Pengajuan Jadwal Baru',
            // Sertakan alasan di pesan
            'message' => "Dosen {$dosen} mengajukan jadwal baru ({$mk} - {$ruang}). Alasan: {$alasan}",
            'icon' => 'heroicon-o-clipboard-document-list',
            'url' => $url,
        ];
        // ----------------------------------------------------
    }

    /**
     * Opsional: Format untuk channel lain (misal: email).
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     // Gunakan '$this->pengajuan' juga di sini jika perlu
    //     $url = PengajuanJadwalResource::getUrl('index');
    //     return (new MailMessage)
    //                 ->subject('Pengajuan Jadwal Baru')
    //                 // ... (konten email) ...
    //                 ->action('Lihat Pengajuan', $url);
    // }
}