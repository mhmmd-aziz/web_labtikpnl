<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Dosen extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'nidn',
            'prodi_id', // <-- UBAH INI
            // ... tambahkan fillable lain
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        // TAMBAHKAN RELASI INI (Asumsi model Anda namanya 'Prodi')
        public function prodi()
        {
            return $this->belongsTo(\App\Models\Prodi::class);
        }
    }