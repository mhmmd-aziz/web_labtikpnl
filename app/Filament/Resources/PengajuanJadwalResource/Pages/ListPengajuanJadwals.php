<?php
// app/Filament/Resources/PengajuanJadwalResource/Pages/ListPengajuanJadwals.php

namespace App\Filament\Resources\PengajuanJadwalResource\Pages;

use App\Filament\Resources\PengajuanJadwalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPengajuanJadwals extends ListRecords
{
    protected static string $resource = PengajuanJadwalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // --- AKTIFKAN DAN BATASI UNTUK DOSEN ---
            Actions\CreateAction::make()
                ->label('Ajukan Jadwal Baru') // Ganti label tombol
                ->visible(fn(): bool => auth()->user()->role === 'dosen'), // Hanya muncul untuk Dosen
            // ----------------------------------------
        ];
    }

    

    // Opsional: Ganti judul halaman berdasarkan role
    public function getTitle(): string
    {
        if (auth()->user()->role === 'dosen') {
            return 'Riwayat Pengajuan Jadwal Saya';
        }
        return 'Approval Pengajuan Jadwal';
    }

     // Opsional: Tampilkan semua riwayat pengajuan untuk dosen
     protected function getTableQuery(): ?Builder
{
    $query = static::getResource()::getEloquentQuery(); // Cara standar memulai query

    if (auth()->check()) { // Pastikan user login
        if (auth()->user()->role === 'dosen') {
            // Dosen melihat semua pengajuannya (pending, approved, rejected)
            // Urutkan berdasarkan yang terbaru
            return $query->where('dosen_id', auth()->id())->latest(); 
        } elseif (auth()->user()->role === 'admin') {
            // Admin hanya melihat yang pending (seperti sebelumnya)
             return $query->where('status', 'pending')->latest();
        }
    }

    // Jika tidak login atau role tidak sesuai, jangan tampilkan apa-apa
    return $query->whereRaw('1 = 0'); // Query yang pasti return kosong
}
}