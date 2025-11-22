<?php
// database/migrations/..._modify_pengajuan_jadwals_for_time_options.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_jadwals', function (Blueprint $table) {
            // Hapus kolom waktu lama (setelah kolom alasan_dosen)
            $table->dropColumn(['waktu_mulai_baru', 'waktu_selesai_baru']);

            // Tambah kolom baru
            $table->string('hari')->after('mata_kuliah');
            $table->string('tipe_jadwal')->default('rutin')->after('hari'); // 'rutin' atau 'insidental'
            $table->foreignId('start_slot_id')->nullable()->constrained('time_slots')->after('tipe_jadwal'); // Link ke time_slots
            $table->foreignId('end_slot_id')->nullable()->constrained('time_slots')->after('start_slot_id');   // Link ke time_slots
            $table->time('jam_mulai')->nullable()->after('end_slot_id');   // Untuk insidental
            $table->time('jam_selesai')->nullable()->after('jam_mulai'); // Untuk insidental
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_jadwals', function (Blueprint $table) {
            // Kembalikan kolom lama jika rollback
            $table->dateTime('waktu_mulai_baru')->nullable()->after('alasan_dosen');
            $table->dateTime('waktu_selesai_baru')->nullable()->after('waktu_mulai_baru');

            // Hapus kolom baru
            $table->dropForeign(['start_slot_id']);
            $table->dropForeign(['end_slot_id']);
            $table->dropColumn(['hari', 'tipe_jadwal', 'start_slot_id', 'end_slot_id', 'jam_mulai', 'jam_selesai']);
        });
    }
};