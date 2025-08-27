<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class SpecialDay extends Model
{
    //
    use HasFactory;
    // Atributos asignables
    protected $fillable = [
        'name',
        'date',
        'is_paid',
        'recurring' 
    ];
    // Casts de atributos
    protected $casts = [
        'date' => 'date:Y-m-d',
        'is_paid' => 'boolean',
        'recurring' => 'boolean',
    ];

    // Scopes que sirven para filtrar los días festivos
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }
    // Scope para filtrar días festivos no pagados
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }
    // Scope para filtrar días festivos recurrentes
    public function scopeRecurring($query)
    {
        return $query->where('recurring', true);
    }
    // Scope para filtrar días festivos no recurrentes
    public function scopeNonRecurring($query)
    {
        return $query->where('recurring', false);
    }
    // Scope para filtrar días festivos por año
    public function scopeYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }
    // Formatear la fecha
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->date)->format('d/m/Y'),
        );
    }
    // Formatear el nombre del día
    protected function dayName(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->date)->translatedFormat('l'),
        );
    }
    // Verificar si una fecha es un día festivo
    public static function isHoliday($date)
    {   // convierte el atributo $date a tipo Carbon (fecha)
        $date = Carbon::parse($date)->format('Y-m-d');
        
        return static::where('date', $date)
            ->orWhere(function($query) use ($date) {
                $query->where('recurring', true)
                    ->whereRaw("DATE_FORMAT(date, '%m-%d') = DATE_FORMAT(?, '%m-%d')", [$date]);
            })
            ->exists();
    }
    // Generar días festivos recurrentes para un año específico
    public static function generateRecurringHolidays($year)
    {
        $recurringHolidays = static::recurring()->get();
        $generated = [];
        // Generar días festivos para el nuevo año
        foreach ($recurringHolidays as $holiday) {
            $newDate = Carbon::create($year, $holiday->date->month, $holiday->date->day);
            $exists = static::where('date', $newDate->format('Y-m-d'))->exists();
            // Verificar si el día festivo ya existe
            if (!$exists) {
                $newHoliday = static::create([
                    'name' => $holiday->name,
                    'date' => $newDate,
                    'is_paid' => $holiday->is_paid,
                    'recurring' => true,
                ]);
                
                $generated[] = $newHoliday;
            }
        }

        return $generated;
    }
    // Obtener un día festivo por fecha
    public static function getHoliday($date = null)
    {
        $date = $date ? Carbon::parse($date) : now();
        return static::where('date', $date->format('Y-m-d'))->first();
    }
}