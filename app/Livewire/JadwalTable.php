<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Jadwal;
use App\Models\Room;
use Carbon\Carbon;

class JadwalTable extends Component
{
    public function render()
    {
        // === SEMUA LOGIKA DARI CONTROLLER ANDA PINDAHKAN KE SINI ===
        $hariIni = Carbon::now()->locale('id_ID')->translatedFormat('l');
        $semuaRuang = Room::orderBy('nama_ruang')->get();
        $semuaJam = [
            ['mulai' => '07:30', 'selesai' => '08:20'],
            ['mulai' => '08:20', 'selesai' => '09:10'],
            ['mulai' => '09:10', 'selesai' => '10:00'],
            ['mulai' => '10:20', 'selesai' => '11:10'],
            ['mulai' => '11:10', 'selesai' => '12:00'],
            ['mulai' => '12:00', 'selesai' => '12:50'],
            ['mulai' => '13:30', 'selesai' => '14:20'],
            ['mulai' => '14:20', 'selesai' => '15:10'],
            ['mulai' => '15:10', 'selesai' => '16:00'],
            ['mulai' => '16:20', 'selesai' => '17:10'],
            ['mulai' => '17:10', 'selesai' => '18:00'],
        ];

        $jadwalHariIni = Jadwal::where('hari', $hariIni)
            ->with(['room', 'kelas', 'dosen'])
            ->orderBy('jam_mulai')
            ->get();
            
        $scheduleData = [];

        foreach ($jadwalHariIni as $jadwal) {
            $jadwalMulai = Carbon::parse($jadwal->jam_mulai);
            $jadwalSelesai = Carbon::parse($jadwal->jam_selesai);
            $startIndex = -1;
            $endIndex = -1;

            foreach ($semuaJam as $index => $slot) {
                $slotMulai = Carbon::parse($slot['mulai']);
                $slotSelesai = Carbon::parse($slot['selesai']);

                if ($startIndex == -1 && $jadwalMulai->gte($slotMulai) && $jadwalMulai->lt($slotSelesai)) {
                    $startIndex = $index;
                }
                if ($jadwalSelesai->gt($slotMulai) && $jadwalSelesai->lte($slotSelesai)) {
                    $endIndex = $index;
                }
            }
            
            if ($startIndex !== -1 && $endIndex !== -1 && isset($jadwal->room_id)) {
                $rowSpan = ($endIndex - $startIndex) + 1;
                $scheduleData[$startIndex][$jadwal->room_id] = [
                    'data' => $jadwal,
                    'span' => $rowSpan,
                ];
                for ($i = 1; $i < $rowSpan; $i++) {
                    if (isset($semuaJam[$startIndex + $i])) {
                        $scheduleData[$startIndex + $i][$jadwal->room_id] = 'occupied';
                    }
                }
            }
        }

        // Kirim data ke view milik komponen ini
        return view('livewire.jadwal-table', [
            'hariIni' => $hariIni,
            'semuaRuang' => $semuaRuang,
            'semuaJam' => $semuaJam,
            'scheduleData' => $scheduleData,
        ]);
    }
}