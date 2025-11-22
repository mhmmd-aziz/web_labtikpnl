<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanJadwal extends Model
{
    use HasFactory;

    // Izinkan semua kolom ini diisi
    protected $guarded = [];

    // SINKRON: Relasi ke Dosen
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // SINKRON: Relasi ke Ruangan
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function kelas(): BelongsTo
    {
        // Pastikan 'Kelas' adalah nama model Kelas kamu dan 'class_id' adalah foreign key
        return $this->belongsTo(Kelas::class, 'class_id');
    }
}