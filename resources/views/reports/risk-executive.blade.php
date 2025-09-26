<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Ejecutivo de Riesgos</title>
    <style>
        @charset "UTF-8";
        @page {
            margin: 4cm 1.5cm 2.5cm 1.5cm;
        }
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }

        /* Header & Footer */
        header {
            position: fixed;
            top: -3cm;
            left: 0cm;
            right: 0cm;
            height: 2.5cm;
            text-align: center;
        }
        footer {
            position: fixed;
            bottom: -2cm;
            left: 0cm;
            right: 0cm;
            height: 1.5cm;
            text-align: center;
            font-size: 9px;
            color: #777;
        }
        footer .page-number:before {
            content: "Página " counter(page);
        }
        .logo {
            width: 150px;
            height: auto;
            /* In a real scenario, you'd have a path to your logo */
            /* content: url('path/to/your/logo.png'); */
            border: 1px dashed #ccc;
            padding: 10px;
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-bottom: 10px;
        }

        /* Typography */
        h1 {
            color: #1a237e; /* Dark blue */
            font-size: 22px;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #3949ab; /* Indigo */
            padding-bottom: 10px;
        }
        h2 {
            color: #283593; /* Indigo darken-1 */
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            border-bottom: 1px solid #9fa8da; /* Indigo lighten-3 */
            padding-bottom: 5px;
            page-break-after: avoid;
        }
        h3 {
            color: #3949ab; /* Indigo */
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            page-break-after: avoid;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #e8eaf6; /* Indigo lighten-5 */
            color: #1a237e; /* Dark blue */
            font-weight: bold;
            text-align: center;
        }
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        /* Specific Table Styles */
        .info-table td {
            border: none;
            padding: 5px 10px;
            background-color: transparent !important;
        }
        .info-table strong {
            color: #283593;
            width: 200px;
            display: inline-block;
        }
        .totals-table {
            table-layout: fixed; /* Ensures columns respect the defined widths */
        }
        .totals-table td {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            padding: 12px;
        }
        .risk-details-table td, .action-plans-table td {
            text-align: left;
        }
        .risk-details-table td:first-child, .action-plans-table td:first-child {
            text-align: center;
            font-weight: bold;
            width: 40px;
        }

        /* Risk Level Colors */
        .risk-bajo { background-color: #c8e6c9 !important; } /* Green 100 */
        .risk-moderado { background-color: #fff9c4 !important; } /* Yellow 100 */
        .risk-alto { background-color: #ffcc80 !important; } /* Orange 100 */
        .risk-critico { background-color: #ffcdd2 !important; } /* Red 100 */

        .text-risk-bajo { color: #2e7d32; }
        .text-risk-moderado { color: #f9a825; }
        .text-risk-alto { color: #ef6c00; }
        .text-risk-critico { color: #c62828; }

        /* Heatmap */
        .heatmap {
            border: 1px solid #5c6bc0;
        }
        .heatmap th, .heatmap td {
            width: 18%;
            height: 50px;
            text-align: center;
            vertical-align: middle;
        }
        .heatmap .impact-label {
            width: 10%;
            font-weight: bold;
            background-color: #e8eaf6;
        }
        .heatmap .probability-label {
            font-weight: bold;
            background-color: #e8eaf6;
        }
        .heatmap td {
            font-size: 16px;
            font-weight: bold;
        }

        /* Other */
        ul {
            padding-left: 20px;
            list-style-type: disc;
        }
        li {
            margin-bottom: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .summary-text {
            font-size: 11px;
            text-align: justify;
            margin-bottom: 20px;
        }
        .conclusion-box {
            background-color: #f5f5f5;
            border-left: 4px solid #3949ab;
            padding: 15px;
            margin-top: 20px;
        }

        /* New styles for risk blocks */
        .risk-block {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background-color: #fdfdfd;
            border-radius: 4px;
        }
        .risk-block-header {
            display: block;
            margin-bottom: 10px;
        }
        .risk-block-header h4 {
            color: #1a237e;
            margin: 0;
            font-size: 13px;
            display: inline-block;
        }
        .risk-summary-table {
            margin-bottom: 15px !important;
        }
        .cause-control-block {
            margin-top: 10px;
            padding-left: 15px;
            border-left: 3px solid #c5cae9; /* Indigo lighten-4 */
        }
        .cause-control-block ul {
            padding-left: 25px;
            margin-top: 5px;
            list-style-type: circle;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Logo de la Compañía</div>
        <p>Informe Confidencial</p>
    </header>

    <footer>
        <div class="page-number"></div>
    </footer>

    <main>
        <div class="container">
            <h1>INFORME EJECUTIVO DE RIESGOS</h1>

            <table class="info-table">
                <tr>
                    <td><strong>PROCESO:</strong></td>
                    <td>{{ $process->title }}</td>
                </tr>
                <tr>
                    <td><strong>SUBPROCESO:</strong></td>
                    <td>{{ $subprocess->title }}</td>
                </tr>
                <tr>
                    <td><strong>LÍDER DEL PROCESO:</strong></td>
                    <td>{{ $subprocess->leader->name }}</td>
                </tr>
                <tr>
                    <td><strong>FECHA DE DILIGENCIAMIENTO:</strong></td>
                    <td>{{ now()->format('d/m/Y') }}</td>
                </tr>
            </table>

            <div class="page-break"></div>

            <h2>Resumen Ejecutivo</h2>
            <p class="summary-text">
                Este informe presenta un análisis de los riesgos identificados para el proceso de <strong>{{ $process->title }}</strong> y el subproceso de <strong>{{ $subprocess->title }}</strong>. Se detallan los riesgos inherentes, los controles existentes y el nivel de riesgo residual resultante. El objetivo es proporcionar una visión clara del perfil de riesgo actual y facilitar la toma de decisiones para la gestión y mitigación de los mismos. A continuación, se presenta un resumen cuantitativo y cualitativo de la situación.
            </p>

            <h3>Totales por Nivel de Riesgo Residual</h3>
            <table class="totals-table">
                <thead>
                    <tr>
                        @php
                            // Get all risk levels, ordered by their ID, to ensure a consistent sort order.
                            $orderedLevels = \App\Models\RiskLevel::orderBy('id')->get();
                            $columnWidth = $orderedLevels->isNotEmpty() ? 100 / $orderedLevels->count() : 0;
                        @endphp
                        @foreach ($orderedLevels as $level)
                            <th class="risk-{{ \Illuminate\Support\Str::slug($level->title) }}" style="width: {{ $columnWidth }}%;">{{ ucfirst($level->title) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($orderedLevels as $level)
                            <td class="risk-{{ \Illuminate\Support\Str::slug($level->title) }}">{{ $totals[strtolower($level->title)] ?? 0 }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>

            {{-- This is a placeholder for a heatmap. It requires data preparation in the controller.
                 You would need to pass $impacts, $probabilities, and $heatmapData.
                 $impacts and $probabilities should be collections of your impact/probability models, ordered by level.
                 $heatmapData should be a 2D array: $heatmapData[$impact->id][$probability->id] = count.
            --}}
            @if(isset($heatmapData) && isset($impacts) && isset($probabilities) && isset($matrix))
            <h2>Mapa de Calor de Riesgo Residual</h2>
            <table class="heatmap">
                <thead>
                    <tr>
                        <th class="impact-label">Impacto ↓</th>
                        @foreach($probabilities as $probability)
                            <th class="probability-label">{{ $probability->title }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($impacts as $impact)
                    <tr>
                        <th class="impact-label">{{ $impact->title }}</th>
                        @foreach($probabilities as $probability)
                            @php
                                $count = $heatmapData[$impact->id][$probability->id] ?? 0;
                                $level = $matrix->firstWhere(fn($item) => $item->impact_id == $impact->id && $item->probability_id == $probability->id)?->level;
                                $class = $level ? 'risk-' . \Illuminate\Support\Str::slug($level->title) : '';
                            @endphp
                            <td class="{{ $class }}">{{ $count > 0 ? $count : '' }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif

            <div class="page-break"></div>

            <h2>Análisis Detallado de Riesgos</h2>
            @php
                $groupedRisks = $risks->groupBy(function ($risk) {
                    return $risk->residualLevelCalculated?->title ?? 'Sin Clasificar';
                });
                $order = ['Crítico', 'Alto', 'Moderado', 'Bajo', 'Sin Clasificar'];
                $sortedGroupedRisks = collect($order)->mapWithKeys(function ($level) use ($groupedRisks) {
                    return [$level => $groupedRisks->get($level, collect())];
                });
            @endphp

            @foreach ($sortedGroupedRisks as $level => $levelRisks)
                @if ($levelRisks->isNotEmpty())
                    <h3>Riesgos con Nivel Residual: <span class="text-risk-{{ \Illuminate\Support\Str::slug($level) }}">{{ $level }}</span> ({{ $levelRisks->count() }})</h3>

                    @foreach ($levelRisks as $risk)
                        <div class="risk-block risk-{{ \Illuminate\Support\Str::slug($risk->residualLevelCalculated?->title ?? '') }}">
                            <div class="risk-block-header">
                                <h4>{{ $risk->classification_code }}: {{ $risk->risk_description }}</h4>
                            </div>

                            <table class="risk-summary-table">
                                <thead>
                                    <tr>
                                        <th>Impacto Inherente</th>
                                        <th>Probabilidad Inherente</th>
                                        <th>Nivel Inherente</th>
                                        <th>Calificación Control</th>
                                        <th>Nivel Residual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">{{ $risk->inherentImpact?->title }}</td>
                                        <td style="text-align: center;">{{ $risk->inherentProbability?->title }}</td>
                                        <td style="text-align: center;">{{ $risk->inherentLevel?->title }}</td>
                                        <td style="text-align: center;">{{ $risk->controlGeneralQualificationCalculated?->title }}</td>
                                        <td style="text-align: center; font-weight: bold;">{{ $risk->residualLevelCalculated?->title }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            @forelse ($risk->potentialCauses as $cause)
                                <div class="cause-control-block">
                                    <strong>Causa:</strong> {{ $cause->title }}
                                    <ul>
                                        @forelse ($cause->controls as $control)
                                            <li><strong>Control:</strong> {{ $control->title }}</li>
                                        @empty
                                            <li>No existen controles asociados para esta causa.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            @empty
                                <p>No se encontraron causas para este riesgo.</p>
                            @endforelse
                        </div>
                    @endforeach
                @endif
            @endforeach

            @if ($risks->pluck('actions')->flatten()->isNotEmpty())
                <div class="page-break"></div>
                <h2>Planes de Acción Propuestos</h2>
                <table class="action-plans-table">
                    <thead>
                        <tr>
                            <th>ID Riesgo</th>
                            <th>Acción</th>
                            <th>Responsable</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $riskCounter = 0;
                        @endphp
                        @foreach ($sortedGroupedRisks as $level => $levelRisks)
                            @foreach ($levelRisks as $risk)
                                @php $riskCounter++; @endphp
                                @foreach ($risk->actions ?? [] as $action)
                                <tr>
                                    <td>{{ $risk->classification_code }}</td>
                                    <td>{{ $action->title }}</td>
                                    <td>{{ $action->responsibleBy->name }}</td>
                                    <td style="text-align: center;">{{ optional($action->start_date)->format('d/m/Y') }}</td>
                                    <td style="text-align: center;">{{ optional($action->end_date)->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
            
            <div class="page-break"></div>
            <h2>Conclusiones y Recomendaciones</h2>
            <div class="conclusion-box">
                <ul>
                    <li>
                        Se ha identificado un total de <strong>{{ $risks->count() }}</strong> riesgos, distribuidos de la siguiente manera:
                        @php
                            $summaryParts = [];
                            if (isset($totals) && is_array($totals)) {
                                foreach (\App\Models\RiskLevel::orderBy('id')->get() as $level) {
                                    $count = $totals[strtolower($level->title)] ?? 0;
                                    if ($count > 0) {
                                        $summaryParts[] = "<strong>{$count}</strong> " . e(ucfirst($level->title));
                                    }
                                }
                            }
                        @endphp
                        {!! implode(', ', $summaryParts) !!}.
                    </li>
                    <li>Es fundamental la divulgación de estos resultados al equipo de trabajo para fomentar una cultura de gestión de riesgos.</li>
                    <li>Se deben definir e implementar los planes de tratamiento propuestos para los riesgos, especialmente aquellos con nivel residual Alto y Crítico, para disminuir su impacto y/o probabilidad.</li>
                    <li>Se recomienda realizar un monitoreo continuo al perfil de riesgos del proceso, con revisiones periódicas para evaluar la efectividad de los controles y planes de acción.</li>
                    <li>Es crucial informar y reportar oportunamente cualquier materialización de riesgos o cambios significativos en el entorno que puedan afectar el perfil de riesgo del proceso.</li>
                </ul>
            </div>

            <div class="page-break"></div>
            <h2>Glosario de Términos</h2>
            <table class="info-table">
                <tr>
                    <td><strong>Riesgo Inherente:</strong></td>
                    <td>El nivel de riesgo antes de considerar la efectividad de los controles existentes. Se determina a partir del impacto y la probabilidad originales del riesgo.</td>
                </tr>
                <tr>
                    <td><strong>Control:</strong></td>
                    <td>Cualquier medida, proceso o acción que modifica el riesgo. Los controles pueden ser preventivos, detectivos o correctivos.</td>
                </tr>
                <tr>
                    <td><strong>Calificación del Control:</strong></td>
                    <td>Evaluación de la efectividad de los controles implementados para mitigar un riesgo.</td>
                </tr>
                <tr>
                    <td><strong>Riesgo Residual:</strong></td>
                    <td>El nivel de riesgo que permanece después de que se han implementado y considerado los controles. Es el riesgo real al que está expuesto el proceso.</td>
                </tr>
                <tr>
                    <td><strong>Plan de Acción:</strong></td>
                    <td>Una tarea o conjunto de tareas diseñadas para tratar un riesgo, ya sea para reducir su probabilidad, su impacto, o ambos.</td>
                </tr>
            </table>

        </div>
    </main>
</body>
</html>