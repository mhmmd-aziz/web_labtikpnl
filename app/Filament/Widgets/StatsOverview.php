<?php

namespace App\Filament\Widgets; // <-- SUDAH DIPERBAIKI

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    /**
     * Metode ini menentukan siapa yang bisa melihat widget ini.
     * Hanya akan tampil jika role pengguna adalah 'admin'.
     */
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }

    protected function getStats(): array
    {
        $namaHariIni = Carbon::now()->translatedFormat('l');
        $namaHariKemarin = Carbon::now()->subDay()->translatedFormat('l');
        $jadwalHariIni = Jadwal::where('hari', $namaHariIni)->count();
        $jadwalKemarin = Jadwal::where('hari', $namaHariKemarin)->count();
        $perbedaanJadwal = $jadwalHariIni - $jadwalKemarin;

        return [
            Stat::make('Total Ruangan', Room::count())
                ->description(Room::where('created_at', '>=', now()->startOfMonth())->count() . ' ruangan baru bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total User', User::count())
                ->description(User::where('created_at', '>=', now()->startOfWeek())->count() . ' user baru minggu ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Kelas', Kelas::count())
                ->description(Kelas::where('created_at', '>=', now()->startOfMonth())->count() . ' kelas baru bulan ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
        ];
    }
}