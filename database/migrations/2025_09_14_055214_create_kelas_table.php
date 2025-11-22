<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('kelas', function (Blueprint $table) { // Nama tabel 'kelas' (jamak dari 'class' dalam B.Indonesia)
        $table->id();
        $table->foreignId('angkatan_id')->constrained()->cascadeOnDelete();
        $table->string('nama_kelas');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
