<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IsrRangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Obtiene la última configuración legal activa (se asume que LegalConfigurationsSeeder ya corrió)
        $legalConfigId = DB::table('legal_configurations')->orderBy('id', 'desc')->value('id');

        if (! $legalConfigId) {
            $this->command->info('No se encontró legal_configuration. Ejecuta primero LegalConfigurationsSeeder.');
            return;
        }

        DB::table('isr_ranges')->insert([
            [
                'legal_configuration_id' => $legalConfigId,
                'min_amount' => 0.00,
                'max_amount' => 472.00,
                'percentage' => 0.00,    // % aplicado al excedente (guardar como porcentaje)
                'fixed_fee' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'legal_configuration_id' => $legalConfigId,
                'min_amount' => 472.01,
                'max_amount' => 895.24,
                'percentage' => 10.00,   // 10%
                'fixed_fee' => 17.67,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'legal_configuration_id' => $legalConfigId,
                'min_amount' => 895.25,
                'max_amount' => 2038.10,
                'percentage' => 20.00,   // 20%
                'fixed_fee' => 60.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'legal_configuration_id' => $legalConfigId,
                'min_amount' => 2038.11,
                'max_amount' => null,    // rango abierto superior
                'percentage' => 30.00,   // 30%
                'fixed_fee' => 288.57,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
