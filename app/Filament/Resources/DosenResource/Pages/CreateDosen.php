<?php

namespace App\Filament\Resources\DosenResource\Pages;

use App\Filament\Resources\DosenResource;
use App\Models\User; // <-- TAMBAHKAN
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash; // <-- TAMBAHKAN

class CreateDosen extends CreateRecord
{
    protected static string $resource = DosenResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1. Buat User baru dari data form
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Password sudah di-hash oleh Form Resource
            'role' => 'dosen', // <-- INI LOGIKA INTINYA
        ]);

        // 2. Siapkan data Dosen
        $dosenData = [
            'nidn' => $data['nidn'],
            'prodi_id' => $data['prodi_id'],
            // ... (tambahkan field dosen lain jika ada di form) ...
            'user_id' => $user->id, // <-- Hubungkan ke User yang baru dibuat
        ];

        // 3. Buat record Dosen
        return static::getModel()::create($dosenData);
    }
}