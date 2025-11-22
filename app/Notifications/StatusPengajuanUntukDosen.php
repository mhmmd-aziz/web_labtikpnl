<?php
// app/Notifications/StatusPengajuanUntukDosen.php

namespace App\Notifications; // <-- Pastikan namespace ini benar

use App\Models\PengajuanJadwal;
// --- PERBAIKI ATAU TAMBAHKAN USE STATEMENT INI ---
use App\Filament\Resources\PengajuanJadwalResource; // <-- Namespace yang benar adalah App\Filament\Resources
// -------------------------------------------------
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;         // <-- Pastikan ini di-import
use App\Models\TimeSlot; // <-- Pastikan ini di-import

class StatusPengajuanUntukDosen extends Notification
{
    use Queueable;

    public PengajuanJadwal $pengajuan;
    public ?string $alasanPenolakan;

    public function __construct(PengajuanJadwal $pengajuan, ?string $alasanPenolakan = null)
    {
        $this->pengajuan = $pengajuan;
        $this->alasanPenolakan = $alasanPenolakan;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Untuk notif lonceng Filament
    }

    public function toDatabase(object $notifiable): array
    {
        // Eager load relasi jika perlu
        $this->pengajuan->loadMissing(['dosen', 'room', 'kelas']);

        $status = $this->pengajuan->status;
        $mk = $this->pengajuan->mata_kuliah ?? 'N/A';
        $hari = $this->pengajuan->hari ?? 'N/A';
        $jam = '-';
        // Ambil waktu dari pengajuan
        if ($this->pengajuan->tipe_jadwal === 'rutin' && $this->pengajuan->start_slot_id && $this->pengajuan->end_slot_id) {
            $start = TimeSlot::find($this->pengajuan->start_slot_id)?->jam_mulai_formatted ?? '?';
            $end = TimeSlot::find($this->pengajuan->end_slot_id)?->jam_selesai_formatted ?? '?';
            $jam = 'Slot ' . $start . ' - ' . $end;
        } elseif ($this->pengajuan->tipe_jadwal === 'insidental' && $this->pengajuan->jam_mulai && $this->pengajuan->jam_selesai) {
            $jam = Carbon::parse($this->pengajuan->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($this->pengajuan->jam_selesai)->format('H:i');
        }

        $title = $status === 'approved' ? 'Pengajuan Jadwal Disetujui' : 'Pengajuan Jadwal Ditolak';
        $message = "Pengajuan Anda untuk {$mk} ({$hari}, {$jam}) telah {$status}.";
        $icon = $status === 'approved' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle';

        if ($status === 'rejected' && $this->alasanPenolakan) {
            $message .= " Alasan: " . $this->alasanPenolakan;
        }

        // --- PASTIKAN PEMANGGILAN INI BENAR SETELAH IMPORT ---
        // Baris 61 kemungkinan ada di sini:
        $url = PengajuanJadwalResource::getUrl('index');
        // ----------------------------------------------------

        return [
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'url' => $url,
        ];
    }
}