<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Room;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TimeSlot;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate semua tabel
        User::truncate();
        Prodi::truncate();
        Angkatan::truncate();
        Kelas::truncate();
        Room::truncate();
        Jadwal::truncate();
        Dosen::truncate();
        Mahasiswa::truncate();
        TimeSlot::truncate();
        MataKuliah::truncate();

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Buat User Admin
        User::create([
            'name' => 'Admin TIK',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat Prodi
        $prodiTI = Prodi::create([
            'nama' => 'Teknik Informatika',
            'jenjang' => 'D4',
            'kode_prodi' => 'TI'
        ]);
        $prodiTRKJ = Prodi::create([
            'nama' => 'TRKJ',
            'jenjang' => 'D4',
            'kode_prodi' => 'TRKJ'
        ]);

        // 3. Buat Angkatan
        $angkatanTI2023 = Angkatan::create(['prodi_id' => $prodiTI->id, 'nama_angkatan' => 'TI 2023']);
        $angkatanTRKJ2023 = Angkatan::create(['prodi_id' => $prodiTRKJ->id, 'nama_angkatan' => 'TRKJ 2023']);

        // 4. Buat Kelas
        $kelasA = Kelas::create(['angkatan_id' => $angkatanTI2023->id, 'nama_kelas' => 'TI-1A']);
        $kelasB = Kelas::create(['angkatan_id' => $angkatanTI2023->id, 'nama_kelas' => 'TI-1B']);

        // 5. Buat Mahasiswa dan Dosen
        
        // Dosen 1
        $dosenUser1 = User::create(['name' => 'Budi Santoso', 'email' => 'budi@pnl.ac.id', 'password' => Hash::make('password'), 'role' => 'dosen']);
        $dosenRecord1 = Dosen::create([
            'user_id' => $dosenUser1->id,
            'nidn' => '112233001',
            'prodi_id' => $prodiTI->id
        ]);
        
        // Dosen 2
        $dosenUser2 = User::create(['name' => 'Citra Lestari', 'email' => 'citra@pnl.ac.id', 'password' => Hash::make('password'), 'role' => 'dosen']);
        $dosenRecord2 = Dosen::create([
            'user_id' => $dosenUser2->id,
            'nidn' => '112233002',
            'prodi_id' => $prodiTRKJ->id
        ]);
        
        // --- INI BAGIAN YANG DIPERBAIKI ---
        
        // Mahasiswa 1
        $mhsUser1 = User::create(['name' => 'Aziz', 'email' => 'aziz@mhs.pnl.ac.id', 'password' => Hash::make('password'), 'role' => 'mahasiswa']);
        Mahasiswa::create([
            'user_id' => $mhsUser1->id,
            'nim' => '2023001',
            'prodi_id' => $prodiTI->id,
            'angkatan_id' => $angkatanTI2023->id,
            'kelas_id' => $kelasA->id
        ]);

        // Mahasiswa 2
        $mhsUser2 = User::create(['name' => 'Bambang', 'email' => 'bambang@mhs.pnl.ac.id', 'password' => Hash::make('password'), 'role' => 'mahasiswa']);
        Mahasiswa::create([
            'user_id' => $mhsUser2->id,
            'nim' => '2023002',
            'prodi_id' => $prodiTI->id,
            'angkatan_id' => $angkatanTI2023->id,
            'kelas_id' => $kelasA->id
        ]);
        
        // --- AKHIR BAGIAN PERBAIKAN ---


        // 6. Buat Ruangan
        $ruang101 = Room::create(['nama_ruang' => 'GSG 101', 'jenis' => 'Teori']);
        $labJaringan = Room::create(['nama_ruang' => 'Lab Jaringan', 'jenis' => 'Lab']);

        // 7. Buat Mata Kuliah
        $matkulJarkom = MataKuliah::create([
            'kode_matkul' => 'TI001',
            'nama_matkul' => 'Jaringan Komputer',
            'sks' => 3,
            'prodi_id' => $prodiTI->id // Relasi ke Prodi TI
        ]);
        
        $matkulBasisData = MataKuliah::create([
            'kode_matkul' => 'TI002',
            'nama_matkul' => 'Basis Data',
            'sks' => 3,
            'prodi_id' => $prodiTI->id // Relasi ke Prodi TI
        ]);


        // 8. Buat Jadwal
        Jadwal::create([
            'room_id' => $labJaringan->id, 
            'class_id' => $kelasA->id, 
            'dosen_id' => $dosenRecord1->id, 
            'mata_kuliah_id' => $matkulJarkom->id,
            'hari' => 'Senin', 
            'jam_mulai' => '08:00', 
            'jam_selesai' => '10:00'
        ]);
        
        Jadwal::create([
            'room_id' => $ruang101->id, 
            'class_id' => $kelasA->id, 
            'dosen_id' => $dosenRecord2->id, 
            'mata_kuliah_id' => $matkulBasisData->id,
            'hari' => 'Selasa', 
            'jam_mulai' => '10:00', 
            'jam_selesai' => '12:00'
        ]);

        Jadwal::create([
            'room_id' => $ruang101->id, 
            'class_id' => $kelasB->id, 
            'dosen_id' => $dosenRecord2->id, 
            'mata_kuliah_id' => $matkulBasisData->id,
            'hari' => 'Selasa', 
            'jam_mulai' => '13:00', 
            'jam_selesai' => '15:00'
        ]);
    }
}