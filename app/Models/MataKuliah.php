<?php

// app/Models/MataKuliah.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Prodi;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'sks',
        'prodi_id',
    ];

    // Relasi: Satu Mata Kuliah dimiliki oleh satu Program Studi
    // KODE PERBAIKAN (BENAR)
public function programStudi(): BelongsTo
{
    // Ganti menjadi "Prodi", sesuai nama model Anda
    return $this->belongsTo(Prodi::class, 'prodi_id'); 
}
}
