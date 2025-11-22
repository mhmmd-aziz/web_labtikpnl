<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('nidn')->unique();
            
            // HAPUS BARIS INI
            // $table->string('program_studi')->nullable(); 
            
            // GANTI DENGAN INI
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');

            // ... tambahkan kolom lain (gelar, no_telp, dll)
            $table->timestamps();
        });
    }

        public function down(): void
        {
            Schema::dropIfExists('dosens');
        }
    };