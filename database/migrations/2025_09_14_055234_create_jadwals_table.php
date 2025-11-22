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
    Schema::create('jadwals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_id')->constrained()->cascadeOnDelete();
        $table->foreignId('class_id')->constrained('kelas')->cascadeOnDelete();
        $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
       // $table->string('mata_kuliah');
        $table->string('hari');
        $table->time('jam_selesai');
        $table->time('jam_mulai');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
