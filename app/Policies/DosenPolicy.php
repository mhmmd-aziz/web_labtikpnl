<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dosen; // Pastikan ini benar


class DosenPolicy
{
   

    /**
     * Perform pre-authorization checks.
     * HANYA 'admin' yang boleh melakukan segalanya.
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
        return false; // Seharusnya ditangani oleh 'before'
    }

    public function view(User $user, Dosen $model): bool
    {
        return false; // Seharusnya ditangani oleh 'before'
    }

    public function create(User $user): bool
    {
        return false; // Seharusnya ditangani oleh 'before'
    }

    public function update(User $user, Dosen $model): bool
    {
        return false; // Seharusnya ditangani oleh 'before'
    }

    public function delete(User $user, Dosen $model): bool
    {
        return false; // Seharusnya ditangani oleh 'before'
    }
}