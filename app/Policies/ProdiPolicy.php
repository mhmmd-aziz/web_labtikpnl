<?php

namespace App\Policies;

use App\Models\Prodi;
use App\Models\User;

class ProdiPolicy
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

    public function view(User $user, Prodi $prodi): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Prodi $prodi): bool
    {
        return false;
    }

    public function delete(User $user, Prodi $prodi): bool
    {
        return false;
    }
}