<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('nim')->unique();
            
            // HAPUS BARIS INI
            // $table->string('program_studi')->nullable();
            // $table->string('angkatan')->nullable();

            // GANTI DENGAN INI (Asumsi nama tabelnya 'prodis', 'angkatans', dan 'kelas')
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('set null');
            $table->foreignId('angkatan_id')->nullable()->constrained('angkatans')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');

            // ... tambahkan kolom lain
            $table->timestamps();
        });
    }

        public function down(): void
        {
            Schema::dropIfExists('mahasiswas');
        }
    };