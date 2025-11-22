<?php

namespace App\Policies;

use App\Models\Angkatan;
use App\Models\User;

class AngkatanPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Angkatan $angkatan): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Angkatan $angkatan): bool
    {
        return false;
    }

    public function delete(User $user, Angkatan $angkatan): bool
    {
        return false;
    }
}