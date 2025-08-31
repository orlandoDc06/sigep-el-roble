<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SpecialDay;
use Carbon\Carbon;

class SpecialDaySeeder extends Seeder
{
    public function run()
    {
        $holidays = [
            [
                'name' => 'Año Nuevo',
                'date' => '2025-01-01',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Día del Trabajo',
                'date' => '2025-05-01',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Día de la Madre',
                'date' => '2025-05-10',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Día del Padre',
                'date' => '2025-06-15',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Fiestas Agostinas',
                'date' => '2025-08-01',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Día de la Independencia',
                'date' => '2025-09-15',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Día de los Difuntos',
                'date' => '2025-11-02',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Navidad',
                'date' => '2025-12-25',
                'is_paid' => true,
                'recurring' => true,
            ],
            [
                'name' => 'Viernes Santo',
                'date' => '2025-03-29',
                'is_paid' => true,
                'recurring' => false,
            ],
            [
                'name' => 'Sábado Santo',
                'date' => '2025-03-30',
                'is_paid' => true,
                'recurring' => false,
            ],
        ];
        // Crear los días festivos
        foreach ($holidays as $holiday) {
            SpecialDay::firstOrCreate(
                ['name' => $holiday['name'], 'date' => $holiday['date']],
                $holiday
            );
        }

        $this->command->info('Días festivos de El Salvador creados exitosamente');
    }
}