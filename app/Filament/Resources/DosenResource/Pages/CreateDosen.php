<?php

namespace App\Filament\Resources\DosenResource\Pages;

use App\Filament\Resources\DosenResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDosen extends CreateRecord
{
    protected static string $resource = DosenResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // 1. Ambil data user dari array form
        $userData = $data['user'];

        // 2. Buat User baru
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $userData['password'], 
            'role' => 'dosen', 
        ]);

        // 3. Buat record Dosen, hubungkan dengan user_id yang baru dibuat
        return static::getModel()::create([
            'user_id' => $user->id,
            'nidn' => $data['nidn'],
            // Jika form prodi tidak diisi, masukkan null
            'prodi_id' => $data['prodi_id'] ?? null, 
        ]);
    }
}