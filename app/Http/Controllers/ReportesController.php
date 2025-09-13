<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Deduction;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    public function bonosDescuentosPdf()
    {
        $bonuses = Bonus::all();
        $deductions = Deduction::all();

        $pdf = Pdf::loadView('pdf.reportes.bonos-descuentos', compact('bonuses', 'deductions'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('reporte_bonos_descuentos.pdf');
    }
}
