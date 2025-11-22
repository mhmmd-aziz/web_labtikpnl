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
    Schema::table('jadwals', function (Blueprint $table) {
        // Tambahkan foreign key untuk mata kuliah
        $table->foreignId('mata_kuliah_id')
              ->after('id') // Posisikan di awal (opsional)
              ->constrained('mata_kuliahs')
              ->cascadeOnDelete();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('jadwals', function (Blueprint $table) {
        $table->dropForeign(['mata_kuliah_id']);
        $table->dropColumn('mata_kuliah_id');
    });
}
};
