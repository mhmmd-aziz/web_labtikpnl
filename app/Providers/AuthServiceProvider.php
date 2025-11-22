<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Angkatan;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Room;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Policies\AngkatanPolicy;
use App\Policies\JadwalPolicy;
use App\Policies\KelasPolicy;
use App\Policies\ProdiPolicy;
use App\Policies\RoomPolicy;
use App\Policies\UserPolicy;
use App\Policies\DosenPolicy;
use App\Policies\MahasiswaPolicy;



class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Jadwal::class => JadwalPolicy::class,
        Prodi::class => ProdiPolicy::class,
        Angkatan::class => AngkatanPolicy::class,
        Kelas::class => KelasPolicy::class,
        Room::class => RoomPolicy::class,
        User::class => UserPolicy::class,
        Dosen::class => DosenPolicy::class, // <-- Registrasi
        Mahasiswa::class => MahasiswaPolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}