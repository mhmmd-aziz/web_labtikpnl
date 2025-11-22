<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use App\Models\Jadwal;

class NotifikasiJadwalBerubah extends Notification
{
    use Queueable;

    public $jadwal;

    public function __construct(Jadwal $jadwal)
    {
        $this->jadwal = $jadwal;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        // SINKRON: Mengambil data dari model Jadwal Anda
        $dosenName = $this->jadwal->dosen->name;
        $roomName = $this->jadwal->room->nama_ruang;

        return (new WebPushMessage)
            ->title('Perubahan Jadwal!')
            ->icon('/logo.png') // Ganti dengan path logo Anda
            ->body("Jadwal MK {$this->jadwal->mata_kuliah} oleh {$dosenName} diubah ke ruang {$roomName}.")
            ->data(['url' => route('welcome')]); // URL saat notif diklik
    }
}