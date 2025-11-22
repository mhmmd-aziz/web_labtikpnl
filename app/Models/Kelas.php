<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;
    
    protected $table = 'kelas'; // Mendefinisikan nama tabel secara eksplisit

    protected $guarded = [];

    public function angkatan(): BelongsTo
    {
        return $this->belongsTo(Angkatan::class);
    }

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(User::class, 'class_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'class_id');
    }
}