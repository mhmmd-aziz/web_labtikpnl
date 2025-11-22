<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Room;
use App\Models\TimeSlot; // 1. IMPORT MODEL TIMESLOT
use Carbon\Carbon;

class JadwalPublikController extends Controller
{
    /**
     * FUNGSI PRIBADI UNTUK MENGAMBIL DAN MEMPROSES DATA JADWAL
     
     */
    private function getScheduleData()
    {
        $hariIni = Carbon::now()->locale('id_ID')->translatedFormat('l');
        $semuaRuang = Room::orderBy('nama_ruang')->get();

       

        
        // Ambil semua slot waktu dan urutkan berdasarkan jam mulai
        $semuaJam = TimeSlot::orderBy('jam_mulai')->get();

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

            // Loop ini sekarang akan menggunakan data dari database
      
            foreach ($semuaJam as $index => $slot) {
              
                $slotMulai = Carbon::parse($slot->jam_mulai); 
                $slotSelesai = Carbon::parse($slot->jam_selesai); 

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

        // Kembalikan semua data dalam bentuk array
        return [
            'hariIni' => $hariIni,
            'semuaRuang' => $semuaRuang,
            'semuaJam' => $semuaJam, // $semuaJam sekarang adalah collection dari Eloquent
            'scheduleData' => $scheduleData,
        ];
    }

    /**
      * Method untuk halaman utama (welcome), sekarang lebih ringkas.
      */
    public function index()
    {
        $data = $this->getScheduleData();
        return view('welcome', $data);
    }

    /**
      * === METHOD BARU UNTUK HALAMAN JADWAL LENGKAP ===
      * Method ini akan dipanggil oleh rute baru kita.
      */
    public function showFullSchedule()
    {
        $data = $this->getScheduleData();
        // Mengarahkan ke file view baru yang akan kita buat
        return view('jadwal-lengkap', $data);
    }
}