<?php
// database/seeders/TimeSlotSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TimeSlot; // <-- Jangan lupa import modelnya

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semuaJam = [
            ['mulai' => '07:30', 'selesai' => '08:20'],
            ['mulai' => '08:20', 'selesai' => '09:10'],
            ['mulai' => '09:10', 'selesai' => '10:00'],
            ['mulai' => '10:20', 'selesai' => '11:10'],
            ['mulai' => '11:10', 'selesai' => '12:00'],
            ['mulai' => '12:00', 'selesai' => '12:50'],
            ['mulai' => '13:30', 'selesai' => '14:20'],
            ['mulai' => '14:20', 'selesai' => '15:10'],
            ['mulai' => '15:10', 'selesai' => '16:00'],
            ['mulai' => '16:20', 'selesai' => '17:10'],
            ['mulai' => '17:10', 'selesai' => '18:00'],
            ['mulai' => '18:10', 'selesai' => '19:00'],
        ];

        foreach ($semuaJam as $jam) {
            TimeSlot::create([
                'jam_mulai' => $jam['mulai'],
                'jam_selesai' => $jam['selesai'],
            ]);
        }
    }
}