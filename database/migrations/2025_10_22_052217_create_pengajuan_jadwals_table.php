<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_jadwals', function (Blueprint $table) {
            $table->id();
            
            // SINKRON: Link ke tabel users Anda
            $table->foreignId('dosen_id')->constrained('users'); 
            
            // SINKRON: Link ke tabel rooms Anda
            $table->foreignId('room_id')->constrained('rooms'); 

            // Data usulan dari Dosen
            $table->string('mata_kuliah');
            $table->unsignedBigInteger('class_id'); // Kita simpan juga ID kelasnya
            $table->dateTime('waktu_mulai_baru'); // Menggunakan dateTime untuk fleksibilitas (bisa beda hari)
            $table->dateTime('waktu_selesai_baru');
            
            // Info pengajuan
            $table->string('tipe_pengajuan'); // 'ganti' atau 'konsisten'
            $table->text('alasan_dosen');
            $table->string('status')->default('pending');
            
            // SINKRON: ID dari tabel 'jadwals' jika tipenya 'ganti'
            $table->unsignedBigInteger('jadwal_lama_id')->nullable(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_jadwals');
    }
};