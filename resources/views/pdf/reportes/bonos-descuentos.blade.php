<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Bonos y Descuentos</title>
    <style>
        @page { margin: 40px 30px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }

        /* Encabezado */
        .header {
            background: linear-gradient(to right, #2563eb, #4f46e5);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 13px;
            color: #dbeafe;
        }

        /* Contenedor */
        .container {
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
            padding: 20px;
        }

        /* Títulos de sección */
        h2 {
            font-size: 16px;
            margin: 20px 0 10px;
            color: #111827;
            padding-left: 8px;
            border-left: 4px solid #2563eb;
        }

        /* Tablas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f9fafb;
            font-weight: bold;
        }
        .bonos th { background: #dcfce7; }
        .descuentos th { background: #fee2e2; }

        /* Totales */
        .total-row td {
            font-weight: bold;
            background: #f3f4f6;
        }
    </style>
</head>
<body>

    <!-- Encabezado -->
    <div class="header">
        <h1>Reporte de Bonos y Descuentos</h1>
        <p>Generado automáticamente desde el sistema</p>
    </div>

    <!-- Contenido -->
    <div class="container">
        <!-- Bonos -->
        <h2>Bonos</h2>
        <table class="bonos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bonuses as $bonus)
                    <tr>
                        <td>{{ $bonus->name }}</td>
                        <td>${{ number_format($bonus->default_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center; color:#6b7280;">
                            No hay bonos registrados
                        </td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td>Total Bonos</td>
                    <td>${{ number_format($bonuses->sum('default_amount'), 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Descuentos -->
        <h2>Descuentos</h2>
        <table class="descuentos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deductions as $deduction)
                    <tr>
                        <td>{{ $deduction->name }}</td>
                        <td>${{ number_format($deduction->default_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center; color:#6b7280;">
                            No hay descuentos registrados
                        </td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td>Total Descuentos</td>
                    <td>${{ number_format($deductions->sum('default_amount'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
