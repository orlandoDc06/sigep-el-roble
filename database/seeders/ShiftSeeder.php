<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                DB::table('shifts')->insert([
            [
                'name' => 'Turno Mañana',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'is_night_shift' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Turno Tarde',
                'start_time' => '16:00:00',
                'end_time' => '00:00:00',
                'is_night_shift' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Turno Noche',
                'start_time' => '00:00:00',
                'end_time' => '08:00:00',
                'is_night_shift' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
