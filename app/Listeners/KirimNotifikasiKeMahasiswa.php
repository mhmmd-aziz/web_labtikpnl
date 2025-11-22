<?php

namespace App\Listeners;

use App\Events\JadwalDiubah;
use App\Models\User;
use App\Notifications\NotifikasiJadwalBerubah;
use Illuminate\Support\Facades\Notification;

class KirimNotifikasiKeMahasiswa
{
    public function handle(JadwalDiubah $event)
    {
        // Ambil jadwal yang baru diubah dari event
        $jadwal = $event->jadwal;

        // Tentukan siapa penerimanya
        // Idealnya, Anda filter mahasiswa berdasarkan class_id dari jadwal
        $mahasiswas = User::where('role', 'mahasiswa')
                           ->where('class_id', $jadwal->class_id)
                           ->get();

        // Kirim Notifikasi!
        if ($mahasiswas->isNotEmpty()) {
            Notification::send($mahasiswas, new NotifikasiJadwalBerubah($jadwal));
        }
    }
}