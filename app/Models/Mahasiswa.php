<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Mahasiswa extends Model
    {
        use HasFactory;

        protected $fillable = [
            'user_id',
            'nim',
            'prodi_id', // <-- UBAH INI
            'angkatan_id', // <-- UBAH INI
            'kelas_id', // <-- TAMBAH INI
            // ... tambahkan fillable lain
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        // TAMBAHKAN RELASI INI
        // (Asumsi nama model Anda 'Prodi', 'Angkatan', dan 'Kelas')
        public function prodi()
        {
            return $this->belongsTo(\App\Models\Prodi::class);
        }

        public function angkatan()
        {
            return $this->belongsTo(\App\Models\Angkatan::class);
        }

        public function kelas()
        {
            return $this->belongsTo(\App\Models\Kelas::class);
        }
    }