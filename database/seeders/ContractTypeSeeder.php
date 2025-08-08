<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('contract_types')->insert([
            [
                'name' => 'Tiempo Completo',
                'base_salary' => 800.00,
                'working_days_per_week' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medio Tiempo',
                'base_salary' => 400.00,
                'working_days_per_week' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Contrato Temporal',
                'base_salary' => 500.00,
                'working_days_per_week' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
