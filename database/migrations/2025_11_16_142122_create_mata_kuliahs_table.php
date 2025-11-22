<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_matkul')->unique();
            $table->string('nama_matkul');
            $table->integer('sks'); // Satuan Kredit Semester
            //$table->integer('semester'); // Semester berapa matkul ini biasanya diambil
            
            // Relasi ke Program Studi
            $table->foreignId('prodi_id')->constrained('prodis')->cascadeOnDelete();
                 
                  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};