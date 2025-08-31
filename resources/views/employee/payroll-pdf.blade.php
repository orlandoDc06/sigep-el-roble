<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .period {
            font-size: 14px;
            color: #666;
        }
        .employee-info {
            margin-bottom: 25px;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #dee2e6;
        }
        .employee-info h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 45%;
        }
        .info-value {
            width: 45%;
            text-align: right;
        }
        .payroll-sections {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .section {
            width: 48%;
            border: 1px solid #dee2e6;
            padding: 15px;
        }
        .section h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            font-weight: bold;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .income-section h3 {
            color: #28a745;
        }
        .deduction-section h3 {
            color: #dc3545;
        }
        .section-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            padding: 3px 0;
        }
        .section-total {
            border-top: 1px solid #333;
            margin-top: 10px;
            padding-top: 8px;
            font-weight: bold;
        }
        .income-total {
            color: #28a745;
        }
        .deduction-total {
            color: #dc3545;
        }
        .net-salary {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background-color: #28a745;
            color: white;
            font-size: 18px;
            font-weight: bold;
        }
        .details-section {
            margin-top: 25px;
        }
        .details-grid {
            display: flex;
            justify-content: space-between;
        }
        .detail-column {
            width: 30%;
        }
        .detail-column h4 {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }
        .bonus-title { color: #28a745; }
        .deduction-title { color: #dc3545; }
        .advance-title { color: #fd7e14; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 3px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-generated { background-color: #d1ecf1; color: #0c5460; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-paid { background-color: #e2e3e5; color: #383d41; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">FERRETERIA EL ROBLE</div>
        <div class="document-title">PLANILLA DE PAGOS</div>
        <div class="period">
            Período: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}
        </div>
        <div style="margin-top: 10px;">
            Estado: 
            <span class="status-badge 
                @if($payrollDetail->payroll->status === 'pending') status-pending
                @elseif($payrollDetail->payroll->status === 'generated') status-generated
                @elseif($payrollDetail->payroll->status === 'approved') status-approved
                @elseif($payrollDetail->payroll->status === 'paid') status-paid
                @endif">
                @if($payrollDetail->payroll->status === 'pending') Pendiente
                @elseif($payrollDetail->payroll->status === 'generated') Generada
                @elseif($payrollDetail->payroll->status === 'approved') Aprobada
                @elseif($payrollDetail->payroll->status === 'paid') Pagada
                @endif
            </span>
        </div>
    </div>

    <div class="employee-info">
        <h3>INFORMACIÓN DEL EMPLEADO</h3>
        <div class="info-row">
            <span class="info-label">Nombre Completo:</span>
            <span class="info-value">{{ $employee->first_name }} {{ $employee->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">DUI:</span>
            <span class="info-value">{{ $employee->dui }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Sucursal:</span>
            <span class="info-value">{{ $employee->branch->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Salario Base (Mensual):</span>
            <span class="info-value">${{ number_format($payrollDetail->base_salary * 2, 2) }}</span>
        </div>
    </div>

    <div class="payroll-sections">
        <div class="section income-section">
            <h3>INGRESOS</h3>
            <div class="section-row">
                <span>Salario Base (Quincenal)</span>
                <span>${{ number_format($payrollDetail->base_salary, 2) }}</span>
            </div>
            <div class="section-row">
                <span>Bonificaciones</span>
                <span>${{ number_format($payrollDetail->bonuses_total, 2) }}</span>
            </div>
            <div class="section-row">
                <span>Horas Extra</span>
                <span>${{ number_format($payrollDetail->extra_hours_total, 2) }}</span>
            </div>
            <div class="section-row section-total income-total">
                <span>TOTAL INGRESOS</span>
                <span>${{ number_format($payrollDetail->base_salary + $payrollDetail->bonuses_total + $payrollDetail->extra_hours_total, 2) }}</span>
            </div>
        </div>

        <div class="section deduction-section">
            <h3>DEDUCCIONES</h3>
            <div class="section-row">
                <span>ISSS</span>
                <span>${{ number_format($payrollDetail->isss, 2) }}</span>
            </div>
            <div class="section-row">
                <span>AFP</span>
                <span>${{ number_format($payrollDetail->afp, 2) }}</span>
            </div>
            <div class="section-row">
                <span>ISR</span>
                <span>${{ number_format($payrollDetail->isr, 2) }}</span>
            </div>
            <div class="section-row">
                <span>Otros Descuentos</span>
                <span>${{ number_format($payrollDetail->deductions_total, 2) }}</span>
            </div>
            <div class="section-row">
                <span>Anticipos</span>
                <span>${{ number_format($payrollDetail->advances_total, 2) }}</span>
            </div>
            <div class="section-row section-total deduction-total">
                <span>TOTAL DEDUCCIONES</span>
                <span>${{ number_format($payrollDetail->isss + $payrollDetail->afp + $payrollDetail->isr + $payrollDetail->deductions_total + $payrollDetail->advances_total, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="net-salary">
        SALARIO NETO A RECIBIR: ${{ number_format($payrollDetail->net_salary, 2) }}
    </div>

    @if($bonuses->count() > 0 || $deductions->count() > 0 || $advances->count() > 0)
    <div class="details-section">
        <h3 style="text-align: center; margin-bottom: 20px; font-size: 14px;">DETALLES DEL PERÍODO</h3>
        
        <div class="details-grid">
            @if($bonuses->count() > 0)
            <div class="detail-column">
                <h4 class="bonus-title">Bonificaciones</h4>
                @foreach($bonuses as $bonus)
                <div class="detail-row">
                    <span>{{ $bonus->name }}</span>
                    <span>${{ number_format($bonus->pivot->amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if($deductions->count() > 0)
            <div class="detail-column">
                <h4 class="deduction-title">Descuentos</h4>
                @foreach($deductions as $deduction)
                <div class="detail-row">
                    <span>{{ $deduction->name }}</span>
                    <span>${{ number_format($deduction->pivot->amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            @endif

            @if($advances->count() > 0)
            <div class="detail-column">
                <h4 class="advance-title">Anticipos</h4>
                @foreach($advances as $advance)
                <div class="detail-row">
                    <span>{{ $advance->date->format('d/m/Y') }}</span>
                    <span>${{ number_format($advance->amount, 2) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Este documento es un comprobante oficial de planilla de pagos</p>
    </div>
</body>
</html>