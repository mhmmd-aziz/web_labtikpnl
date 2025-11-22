<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * INI ADALAH TEMPLATE UNTUK SEMUA POLICY.
 * Logikanya: Hanya 'admin' yang boleh melakukan segalanya.
 */
class TemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Jika user adalah 'admin', izinkan semuanya.
        if ($user->role === 'admin') {
            return true;
        }

        // Jika bukan admin, tolak akses ke semua method policy di bawah ini.
        return false;
    }

    // Fungsi-fungsi di bawah ini tidak akan pernah dijalankan
    // karena 'before' sudah menangani semuanya.
    
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, $model): bool
    {
        return false;
    }

    public function delete(User $user, $model): bool
    {
        return false;
    }
}