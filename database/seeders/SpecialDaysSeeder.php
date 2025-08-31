<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('special_days')->insert([
            [
                'name' => 'Año Nuevo',
                'date' => '2025-01-01',
                'is_paid' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Día del Trabajo',
                'date' => '2025-05-01',
                'is_paid' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fiestas Agostinas',
                'date' => '2025-08-06',
                'is_paid' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Día de la Independencia',
                'date' => '2025-09-15',
                'is_paid' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Navidad',
                'date' => '2025-12-25',
                'is_paid' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);   
    }
}
