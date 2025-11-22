<?php

namespace App\Filament\Resources\MahasiswaResource\Pages;

use App\Filament\Resources\MahasiswaResource;
use App\Models\User; // <-- TAMBAHKAN
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; // <-- TAMBAHKAN

class CreateMahasiswa extends CreateRecord
{
    protected static string $resource = MahasiswaResource::class;

  protected function handleRecordCreation(array $data): Model
{
    // dd($data); // Coba aktifkan sementara untuk cek struktur baru

    // Ambil data user dari array
    $userData = $data['user'];

    // Buat User baru
    $user = \App\Models\User::create([
        'name' => $userData['name'],
        'email' => $userData['email'],
        'password' => $userData['password'],
        'role' => 'mahasiswa',
    ]);

    // Buat Mahasiswa baru, hubungkan dengan user_id
    return static::getModel()::create([
        'user_id' => $user->id,
        'nim' => $data['nim'],
        'prodi_id' => $data['prodi_id'],
        'angkatan_id' => $data['angkatan_id'],
        'kelas_id' => $data['kelas_id'],
    ]);
}
}