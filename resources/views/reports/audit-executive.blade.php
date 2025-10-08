<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Ejecutivo de Auditoría</title>
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

        /* Specific Styles */
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
        .page-break {
            page-break-after: always;
        }
        .summary-text {
            font-size: 11px;
            text-align: justify;
            margin-bottom: 20px;
        }
        .block {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            page-break-inside: avoid;
            background-color: #fdfdfd;
            border-radius: 4px;
        }
        .block-header h4 {
            color: #1a237e;
            margin: 0;
            font-size: 13px;
            display: inline-block;
        }
        .sub-block {
            margin-top: 10px;
            padding-left: 15px;
            border-left: 3px solid #c5cae9; /* Indigo lighten-4 */
        }
        .sub-block ul {
            padding-left: 25px;
            margin-top: 5px;
            list-style-type: circle;
        }
        .finding-block {
            background-color: #fffde7; /* Yellow lighten-5 */
            border-left: 4px solid #f9a825; /* Yellow darken-3 */
            padding: 10px;
            margin-top: 10px;
        }
        .two-column-layout {
            column-count: 2;
            column-gap: 20px;
            margin-top: 15px;
        }
        .conclusion-box {
            background-color: #f5f5f5;
            border-left: 4px solid #3949ab;
            padding: 15px;
            margin-top: 20px;
        }
        .conclusion-box ul {
            padding-left: 20px;
            list-style-type: disc;
        }
        .action-plans-table td:first-child {
            text-align: center;
            font-weight: bold;
            width: 40px;
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
            <h1>INFORME EJECUTIVO DE AUDITORÍA</h1>

            <table class="info-table">
                <tr>
                    <td><strong>PROCESO:</strong></td>
                    <td>{{ $audit->process->title }}</td>
                </tr>
                <tr>
                    <td><strong>SUBPROCESO:</strong></td>
                    <td>{{ $audit->subProcess->title }}</td>
                </tr>
                <tr>
                    <td><strong>LÍDER DEL PROCESO:</strong></td>
                    <td>{{ $audit->subProcess->leader->name }}</td>
                </tr>
                <tr>
                    <td><strong>FECHA DE DILIGENCIAMIENTO:</strong></td>
                    <td>{{ now()->format('d/m/Y') }}</td>
                </tr>
            </table>

            <div class="page-break"></div>

            <h2>Resumen Ejecutivo</h2>
            <p class="summary-text">
                Este informe presenta los resultados de la auditoría interna realizada al proceso de <strong>{{ $audit->process->title }}</strong> y al subproceso de <strong>{{ $audit->subProcess->title }}</strong>. Se detallan los ítems de auditoría evaluados, los controles asociados, los hallazgos identificados y los planes de acción propuestos. El objetivo es proporcionar una visión clara sobre la conformidad y eficacia de los procesos auditados, y facilitar la toma de decisiones para la mejora continua.
            </p>

            <h2>Detalles de la Auditoría</h2>
            <div class="block">
                <div class="block-header">
                    <h4>{{ $audit->classification_code }}: {{ $audit->title }}</h4>
                </div>
                <div class="two-column-layout">
                    <p class="summary-text" style="margin-top: 0;"><strong>Objetivo:</strong> {{ $audit->objective }}</p>
                    <p class="summary-text" style="margin-top: 0;"><strong>Alcance:</strong> {{ $audit->scope }}</p>
                </div>
                <hr style="border: none; border-top: 1px solid #eee; margin: 15px 0;">
                <p class="summary-text" style="margin-bottom: 0;">
                    <strong>Observaciones del Auditor:</strong><br>
                    {!! nl2br(e($audit->observations ?? 'Sin finalizar')) !!}
                </p>
            </div>
            <div class="block">
                <table style="margin-bottom: 0;">
                    <thead>
                        <tr>
                            <th>Fecha Auditoría</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Calificación</th>
                            <th>Valor Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">{{ $audit->audit_date ? \Carbon\Carbon::parse($audit->audit_date)->format('d/m/Y') : '-' }}</td>
                            <td style="text-align: center;">{{ $audit->priority?->title }}</td>
                            <td style="text-align: center;">{{ $audit->status?->label }}</td>
                            <td style="text-align: center;">{{ $audit->internalAuditQualification?->title ?? 'Sin calificar' }}</td>
                            <td style="text-align: center;">{{ isset($audit->qualification_value) ? $audit->qualification_value . '%' : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="page-break"></div>
            
            <h2>Análisis Detallado de Ítems de Auditoría</h2>

            @forelse ($audit->auditItems as $item)
                <h3>
                    Actividad: {{ $item->activity?->title }}
                </h3>

                @forelse ($item->controls as $control)
                    <div class="block">
                        <div class="block-header">
                            <h4>Control: {{ $control->title }}</h4>
                        </div>
                        <table>
                            <tbody>
                                <tr>
                                    <th>Naturaleza</th>
                                    <td>{{ $control->natureOfControl?->title }}</td>
                                    <th>Tipo de control</th>
                                    <td>{{ $control->controlType?->title }}</td>
                                </tr>
                                <tr>
                                    <th>Periodicidad</th>
                                    <td>{{ $control->controlPeriodicity?->title }}</td>
                                    <th>Tipo de Efecto</th>
                                    <td>{{ $control->effectType?->title ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Nivel</th>
                                    <td>{{ $control->level?->title ?? '-' }}</td>
                                    <th>Clasificación</th>
                                    <td>{{ $control->classification?->title ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        @forelse ($control->findings as $finding)
                            <div class="finding-block">
                                <strong>Hallazgo:</strong> {{ $finding->title }} ({{ $finding->findingType?->title }})
                                <p style="margin-top: 5px; margin-bottom: 0;">{{ $finding->description }}</p>
                            </div>
                        @empty
                            <p>No se encontraron hallazgos para este control.</p>
                        @endforelse
                    </div>
                @empty
                    <p>No se encontraron controles para este ítem de auditoría.</p>
                @endforelse

            @empty
                <p>No se encontraron ítems para esta auditoría.</p>
            @endforelse

            @php
                $allActions = $audit->auditItems->pluck('controls')->flatten()->pluck('findings')->flatten()->pluck('actions')->flatten();
            @endphp

            @if ($allActions->isNotEmpty())
                <div class="page-break"></div>
                <h2>Planes de Acción</h2>
                <table class="action-plans-table">
                    <thead>
                        <tr>
                            <th>Hallazgo</th>
                            <th>Acción</th>
                            <th>Responsable</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($audit->auditItems as $item)
                            @foreach ($item->controls as $control)
                                @foreach ($control->findings as $finding)
                                    @foreach ($finding->actions as $action)
                                        <tr>
                                            <td>{{ $finding->title }}</td>
                                            <td>{{ $action->title }}</td>
                                            <td>{{ $action->responsibleBy?->name }}</td>
                                            <td style="text-align: center;">{{ optional($action->start_date)->format('d/m/Y') }}</td>
                                            <td style="text-align: center;">{{ optional($action->end_date)->format('d/m/Y') }}</td>
                                            <td style="text-align: center;">{{ $action->status?->label }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="page-break"></div>
            <h2>Conclusiones y Recomendaciones</h2>
            <div class="conclusion-box">
                @php
                    $totalItems = $audit->auditItems->count();
                    $totalControls = $audit->auditItems->pluck('controls')->flatten()->count();
                    $totalFindings = $allActions->groupBy('finding_id')->count();
                @endphp
                <ul>
                    <li>La auditoría evaluó un total de <strong>{{ $totalItems }}</strong> ítem(s), que abarcan <strong>{{ $totalControls }}</strong> control(es) clave del proceso.</li>
                    <li>Se identificaron <strong>{{ $totalFindings }}</strong> hallazgo(s) que requieren atención, para los cuales se han definido <strong>{{ $allActions->count() }}</strong> plan(es) de acción.</li>
                    @if ($audit->internalAuditQualification)
                        <li>La calificación general de la auditoría fue <strong>'{{ $audit->internalAuditQualification->title }}'</strong> con un valor de <strong>{{ $audit->qualification_value ?? 0 }}%</strong>, reflejando el estado actual de conformidad y eficacia de los controles.</li>
                    @endif
                    <li>Es fundamental que los responsables designados ejecuten los planes de acción en los plazos establecidos para mitigar los riesgos asociados a los hallazgos.</li>
                    <li>Se recomienda realizar un seguimiento periódico al cumplimiento de los planes de acción y verificar la eficacia de las medidas implementadas.</li>
                    <li>Los resultados de esta auditoría deben ser comunicados a todo el equipo del proceso para fomentar una cultura de control interno y mejora continua.</li>
                </ul>
            </div>

        </div>
    </main>
</body>
</html>