<?php

namespace App\Filament\Resources\JadwalResource\Pages;

use App\Filament\Resources\JadwalResource;
use App\Models\Jadwal;
use App\Models\TimeSlot;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;

class CreateJadwal extends CreateRecord
{
    protected static string $resource = JadwalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // =====================================================================
        // LANGKAH 1: Tentukan jam_mulai dan jam_selesai final berdasarkan tipe jadwal
        // =====================================================================
        $finalJamMulai = null;
        $finalJamSelesai = null;

        if ($data['tipe_jadwal'] === 'rutin') {
            // Ambil waktu dari TimeSlot jika tipenya rutin
            $startSlot = TimeSlot::find($data['start_slot_id']);
            $endSlot = TimeSlot::find($data['end_slot_id']);
            
            if ($startSlot && $endSlot) {
                $finalJamMulai = $startSlot->jam_mulai;
                $finalJamSelesai = $endSlot->jam_selesai;
            }
        } else {
            // Ambil waktu langsung dari data jika tipenya insidental
            $finalJamMulai = $data['jam_mulai'];
            $finalJamSelesai = $data['jam_selesai'];
        }

        // Jika karena suatu hal waktu tidak terdefinisi, hentikan proses
        if (!$finalJamMulai || !$finalJamSelesai) {
            throw ValidationException::withMessages(['tipe_jadwal' => 'Gagal menentukan waktu jadwal. Pastikan slot atau waktu terisi dengan benar.']);
        }

        // =====================================================================
        // LANGKAH 2: Jalankan semua validasi Anda menggunakan jam final
        // =====================================================================
        if ($finalJamMulai >= $finalJamSelesai) {
            Notification::make()->danger()->title('Gagal Menyimpan Jadwal')->body('Jam selesai harus setelah jam mulai.')->send();
            throw ValidationException::withMessages(['jam_selesai' => 'Jam selesai harus setelah jam mulai.']);
        }

        $konflik = Jadwal::query()
            ->where('hari', $data['hari'])
            ->where(function (Builder $query) use ($finalJamMulai, $finalJamSelesai) {
                $query->where('jam_mulai', '<', $finalJamSelesai)->where('jam_selesai', '>', $finalJamMulai);
            })
            ->where(function (Builder $query) use ($data) {
                $query->where('room_id', $data['room_id'])
                      ->orWhere('dosen_id', $data['dosen_id'])
                      ->orWhere('class_id', $data['class_id']);
            })
            ->with(['room', 'dosen', 'kelas'])->first();

        if ($konflik) {
            $pesanError = ''; $fieldError = '';
            if ($konflik->room_id == $data['room_id']) {
                $pesanError = "Ruangan {$konflik->room->nama_ruang} sudah dipakai oleh MK '{$konflik->mata_kuliah}'.";
                $fieldError = 'room_id';
            } elseif ($konflik->dosen_id == $data['dosen_id']) {
                $pesanError = "Dosen {$konflik->dosen->name} sudah mengajar MK '{$konflik->mata_kuliah}'.";
                $fieldError = 'dosen_id';
            } elseif ($konflik->class_id == $data['class_id']) {
                $pesanError = "Kelas {$konflik->kelas->nama_kelas} sudah ada jadwal MK '{$konflik->mata_kuliah}'.";
                $fieldError = 'class_id';
            }
            
            Notification::make()->danger()->title('Terjadi Konflik Jadwal')->body($pesanError)->send();
            throw ValidationException::withMessages([$fieldError => $pesanError]);
        }
        
        // =====================================================================
        // LANGKAH 3: Siapkan data final untuk disimpan ke database
        // =====================================================================
        $data['jam_mulai'] = $finalJamMulai;
        $data['jam_selesai'] = $finalJamSelesai;
        
        unset($data['start_slot_id'], $data['end_slot_id']);

        return $data;
    }
}