<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormulasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('formulas')->insert([
            [
                'name' => 'Pago por hora normal',
                'expression' => '(SALARIO_BASE / 30) / 8',
                'type' => 'otros',
                'is_editable' => false,
                'syntax_validated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hora extra normal (17:00 - 19:00)',
                'expression' => 'HORA_NORMAL + (HORA_NORMAL * 0.30)',
                'type' => 'bono', // puedes usar 'overtime' o 'bono' según tu convención
                'is_editable' => true,
                'syntax_validated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hora extra después de las 19:00',
                'expression' => 'HORA_EXTRA_NORMAL * 2',
                'type' => 'bono',
                'is_editable' => true,
                'syntax_validated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bono vacacional (30%)',
                'expression' => 'SALARIO_BASE * 0.30',
                'type' => 'otros',
                'is_editable' => false,
                'syntax_validated' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
