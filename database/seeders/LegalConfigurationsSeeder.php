<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegalConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserta una configuración legal base. Ajusta los valores si lo requieres.
        DB::table('legal_configurations')->insert([
            'afp_percentage' => 7.25,              // % AFP (ejemplo)
            'isss_percentage' => 3.00,             // % ISSS
            'isss_max_cap' => 30.00,               // tope para ISSS
            'minimum_wage' => 408.00,              // salario mínimo
            'vacation_bonus_percentage' => 30.00,  // % bono vacacional
            'year_end_bonus_days' => 15,           // días de aguinaldo (ajustar según ley)
            'income_tax_enabled' => true,
            'start_date' => now()->toDateString(),
            'end_date' => null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
