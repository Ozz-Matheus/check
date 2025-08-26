<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @charset "UTF-8";
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .risk-crítico { background-color: #ff4d4d; }
        .risk-alto { background-color: #ffcc00; }
        .risk-moderado { background-color: #ffff99; }
        .risk-bajo { background-color: #ccffcc; }
    </style>
</head>
<body>
    <h1>Informe Ejecutivo de Riesgos</h1>
    <h2> {{ $process->title }} - {{ $subprocess->title }}</h2>

    <table>
        <thead>
            <tr>
                <th>Riesgo</th>
                <th>Causas</th>
                <th>Controles</th>
                <th>Impacto Inherente</th>
                <th>Probabilidad Inherente</th>
                <th>Nivel Riesgo Inherente</th>
                <th>Calificación Control</th>
                <th>Nivel Riesgo Residual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($risks as $risk)
                @php
                    $levelClass = strtolower($risk->residualLevelCalculated?->title);
                    $validLevels = ['crítico', 'alto', 'moderado', 'bajo'];
                @endphp
                <tr class="{{ in_array($levelClass, $validLevels) ? 'risk-' . $levelClass : '' }}">
                    <td>{{ $risk->risk_description }}</td>
                    <td>
                        @foreach ($risk->potentialCauses as $cause)
                            • {{ $cause->title }}<br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($risk->controls as $control)
                            • {{ $control->title }}<br>
                        @endforeach
                    </td>
                    <td>{{ $risk->inherentImpact?->title }}</td>
                    <td>{{ $risk->inherentProbability?->title }}</td>
                    <td>{{ $risk->inherentLevel?->title }}</td>
                    <td>{{ $risk->controlGeneralQualificationCalculated?->title }}</td>
                    <td>{{ $risk->residualLevelCalculated?->title }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Totales por Nivel de Riesgo Residual</h2>
    <table>
        <thead>
            <tr>
                <th>Crítico</th>
                <th>Alto</th>
                <th>Moderado</th>
                <th>Bajo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totals['crítico'] }}</td>
                <td>{{ $totals['alto'] }}</td>
                <td>{{ $totals['moderado'] }}</td>
                <td>{{ $totals['bajo'] }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Recomendaciones:</strong></p>
    <ul>
        <li>Priorizar análisis sobre riesgos críticos y altos.</li>
        <li>Revisar controles donde se observe calificación deficiente.</li>
        <li>Actualizar datos y monitorear riesgos moderados y bajos periódicamente.</li>
    </ul>
</body>
</html>
