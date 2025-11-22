<?php
// app/Filament/Resources/PengajuanJadwalResource/Pages/ViewPengajuanJadwal.php

namespace App\Filament\Resources\PengajuanJadwalResource\Pages;

use App\Filament\Resources\PengajuanJadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord; // <-- GANTI DARI EditRecord ke ViewRecord

class ViewPengajuanJadwal extends ViewRecord // <-- GANTI DARI EditRecord ke ViewRecord
{
    protected static string $resource = PengajuanJadwalResource::class;

    // Jika ingin menambahkan tombol aksi di halaman view, tambahkan di sini
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         // Actions\EditAction::make(), // Contoh jika mau ada tombol edit
    //     ];
    // }
}