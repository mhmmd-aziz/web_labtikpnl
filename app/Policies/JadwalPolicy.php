<?php

namespace App\Policies;

use App\Models\Jadwal;
use App\Models\User;

class JadwalPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['mahasiswa', 'dosen']);
    }

    public function view(User $user, Jadwal $jadwal): bool
    {
        if ($user->role === 'mahasiswa') {
            return $user->class_id === $jadwal->class_id;
        }
        if ($user->role === 'dosen') {
            return $user->id === $jadwal->dosen_id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Jadwal $jadwal): bool
    {
        return false;
    }

    public function delete(User $user, Jadwal $jadwal): bool
    {
        return false;
    }
}