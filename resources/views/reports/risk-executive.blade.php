<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Ejecutivo de Gestión de Riesgos</title>
    
    {{-- Estilos compartidos --}}
    @include('reports.layout.styles')
    
    <style>
        /* Estilos específicos dinámicos para niveles de riesgo */
        @foreach($allRiskLevels as $level)
            @php
                $slug = \Illuminate\Support\Str::slug($level->title);
                $colorMap = [
                    'success' => ['bg' => '#d4edda', 'text' => '#155724', 'badge' => '#28a745'],
                    'yellow' => ['bg' => '#fff3cd', 'text' => '#856404', 'badge' => '#ffc107'],
                    'warning' => ['bg' => '#ffe5d0', 'text' => '#d63301', 'badge' => '#fd7e14'],
                    'danger' => ['bg' => '#f8d7da', 'text' => '#721c24', 'badge' => '#dc3545'],
                ];
                $colors = $colorMap[$level->color] ?? ['bg' => '#e0e0e0', 'text' => '#333333', 'badge' => '#757575'];
            @endphp
        .risk-{{ $slug }} { 
            background-color: {{ $colors['bg'] }} !important; 
            color: {{ $colors['text'] }} !important; 
        }
        .badge-{{ $slug }} { 
            background-color: {{ $colors['badge'] }}; 
            color: white; 
            padding: 4px 8px; 
            border-radius: 3px; 
            font-weight: bold;
            font-size: 9px;
        }
        @endforeach
    </style>
</head>
<body>
    <header>
        <div class="logo">LOGO DE LA ORGANIZACIÓN</div>
        <p style="color: #e74c3c; font-weight: bold; margin: 0;">DOCUMENTO CONFIDENCIAL</p>
    </header>

    <footer>
        <div class="page-number"></div>
        <p style="margin: 5px 0 0 0;">Informe Ejecutivo de Gestión de Riesgos - {{ now()->format('d/m/Y') }}</p>
    </footer>

    <main>
        <div class="container">
            {{-- PORTADA --}}
            <h1>INFORME EJECUTIVO DE GESTIÓN DE RIESGOS</h1>

            <table class="info-table mb-20">
                <tr>
                    <td>PROCESO</td>
                    <td>{{ $process->title }}</td>
                </tr>
                <tr>
                    <td>SUBPROCESO</td>
                    <td>{{ $subprocess->title }}</td>
                </tr>
                <tr>
                    <td>LÍDER DEL PROCESO</td>
                    <td>{{ $subprocess->leader->name ?? 'No asignado' }}</td>
                </tr>
                <tr>
                    <td>FECHA DE GENERACIÓN</td>
                    <td>{{ now()->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td>PERÍODO DE ANÁLISIS</td>
                    <td>{{ now()->format('Y') }}</td>
                </tr>
            </table>

            {{-- RESUMEN EJECUTIVO --}}
            <h2>1. RESUMEN EJECUTIVO</h2>
            <p style="text-align: justify; margin-bottom: 20px; line-height: 1.6;">
                El presente informe consolida el análisis de riesgos del proceso <strong>{{ $process->title }}</strong>, 
                subproceso <strong>{{ $subprocess->title }}</strong>. Se han identificado un total de 
                <strong>{{ $statistics['total_risks'] }}</strong> riesgos, con 
                <strong>{{ $statistics['total_controls'] }}</strong> controles implementados y 
                <strong>{{ $statistics['total_actions'] }}</strong> acciones propuestas. 
                Este documento proporciona una visión integral del perfil de riesgo actual, incluyendo análisis 
                inherente y residual, para facilitar la toma de decisiones estratégicas en la gestión de riesgos.
            </p>

            {{-- ESTADÍSTICAS CLAVE --}}
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-number">{{ $statistics['total_risks'] }}</span>
                    <span class="stat-label">Total Riesgos</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $statistics['total_controls'] }}</span>
                    <span class="stat-label">Controles</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $statistics['total_causes'] }}</span>
                    <span class="stat-label">Causas</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $statistics['total_actions'] }}</span>
                    <span class="stat-label">Acciones</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $statistics['avg_controls_per_risk'] }}</span>
                    <span class="stat-label">Promedio Controles</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">{{ $statistics['risks_with_actions'] }}</span>
                    <span class="stat-label">Riesgos con Acciones</span>
                </div>
            </div>

            <div class="page-break"></div>

            {{-- COMPARACIÓN INHERENTE VS RESIDUAL --}}
            <h2>2. ANÁLISIS COMPARATIVO: RIESGO INHERENTE VS RESIDUAL</h2>
            
            <div class="section-box" style="margin-bottom: 20px;">
                <div class="section-title">EFECTIVIDAD DE LOS CONTROLES</div>
                <p style="margin: 0; line-height: 1.6;">
                    <strong>Puntuación Total de Riesgo Inherente:</strong> {{ $statistics['total_inherent_score'] }} puntos<br>
                    <strong>Puntuación Total de Riesgo Residual:</strong> {{ $statistics['total_residual_score'] }} puntos<br>
                    <strong>Reducción Absoluta:</strong> {{ $statistics['risk_reduction'] }} puntos 
                    ({{ $statistics['risk_reduction_percentage'] }}%)
                </p>
                <p style="margin-top: 10px; margin-bottom: 0; line-height: 1.6;">
                    @if($statistics['risk_reduction_percentage'] > 0)
                        <span style="color: #27ae60; font-weight: bold;">✓ Los controles han sido EFECTIVOS</span> 
                        reduciendo la exposición al riesgo en un {{ $statistics['risk_reduction_percentage'] }}%.
                    @elseif($statistics['risk_reduction_percentage'] < 0)
                        <span style="color: #e74c3c; font-weight: bold;">⚠ ALERTA:</span> 
                        Se observa un incremento del {{ abs($statistics['risk_reduction_percentage']) }}% en la exposición al riesgo.
                    @else
                        <span style="color: #f39c12; font-weight: bold;">→</span> 
                        Los niveles de riesgo se mantienen constantes.
                    @endif
                </p>
            </div>
            
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">NIVEL DE RIESGO</th>
                        <th style="width: 35%;">RIESGO INHERENTE</th>
                        <th style="width: 35%;">RIESGO RESIDUAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allRiskLevels->reverse() as $level)
                        @php
                            $slug = \Illuminate\Support\Str::slug($level->title);
                            $inherentCount = $inherentTotals[strtolower($level->title)] ?? 0;
                            $residualCount = $residualTotals[strtolower($level->title)] ?? 0;
                            $change = $inherentCount - $residualCount;
                        @endphp
                        <tr>
                            <td class="risk-{{ $slug }}" style="font-weight: bold; text-align: center;">
                                {{ strtoupper($level->title) }}
                            </td>
                            <td class="risk-{{ $slug }}" style="text-align: center; font-size: 20px; font-weight: bold;">
                                {{ $inherentCount }}
                            </td>
                            <td class="risk-{{ $slug }}" style="text-align: center; font-size: 20px; font-weight: bold;">
                                {{ $residualCount }}
                                @if($change > 0)
                                    <span style="color: #27ae60; font-size: 11px; display: block;">
                                        (↓ {{ $change }} reducido{{ $change > 1 ? 's' : '' }})
                                    </span>
                                @elseif($change < 0)
                                    <span style="color: #e74c3c; font-size: 11px; display: block;">
                                        (↑ {{ abs($change) }} aumentado{{ abs($change) > 1 ? 's' : '' }})
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color: #34495e; color: white; font-weight: bold;">
                        <td style="text-align: center;">TOTAL</td>
                        <td style="text-align: center; font-size: 20px;">{{ array_sum($inherentTotals) }}</td>
                        <td style="text-align: center; font-size: 20px;">{{ array_sum($residualTotals) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="section-box">
                <div class="section-title">MOVIMIENTO DE RIESGOS ENTRE NIVELES:</div>
                <ul style="margin: 5px 0;">
                    <li>
                        <strong style="color: #27ae60;">{{ $statistics['risks_improved'] }}</strong> 
                        riesgo{{ $statistics['risks_improved'] != 1 ? 's' : '' }} 
                        <strong>mejoró{{ $statistics['risks_improved'] != 1 ? 'aron' : '' }}</strong> su nivel 
                        (pasó{{ $statistics['risks_improved'] != 1 ? 'aron' : '' }} a un nivel más bajo)
                    </li>
                    <li>
                        <strong style="color: #e74c3c;">{{ $statistics['risks_worsened'] }}</strong> 
                        riesgo{{ $statistics['risks_worsened'] != 1 ? 's' : '' }} 
                        <strong>empeoró{{ $statistics['risks_worsened'] != 1 ? 'aron' : '' }}</strong> su nivel 
                        (pasó{{ $statistics['risks_worsened'] != 1 ? 'aron' : '' }} a un nivel más alto)
                    </li>
                    <li>
                        <strong style="color: #95a5a6;">{{ $statistics['risks_unchanged'] }}</strong> 
                        riesgo{{ $statistics['risks_unchanged'] != 1 ? 's' : '' }} 
                        <strong>se mantuvo{{ $statistics['risks_unchanged'] != 1 ? 'vieron' : '' }}</strong> en el mismo nivel
                    </li>
                </ul>
            </div>

            <div class="page-break"></div>

            {{-- MAPAS DE CALOR --}}
            <h2>3. MATRICES DE RIESGO (MAPAS DE CALOR)</h2>

            <h3>3.1 Mapa de Calor - Riesgo Inherente</h3>
            <table class="heatmap">
                <thead>
                    <tr>
                        <th class="axis-label">IMPACTO →<br>PROBABILIDAD ↓</th>
                        @foreach($probabilities as $probability)
                            <th class="axis-label">
                                {{ $probability->title }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($impacts->reverse() as $impact)
                        <tr>
                            <th class="axis-label">{{ $impact->title }}</th>
                            @foreach($probabilities as $probability)
                                @php
                                    $count = $inherentHeatmapData[$impact->id][$probability->id] ?? 0;
                                    $level = $matrixLevels[$impact->id][$probability->id] ?? null;
                                    $class = $level ? 'risk-' . \Illuminate\Support\Str::slug($level->title) : '';
                                @endphp
                                <td class="{{ $class }}">
                                    {{ $count > 0 ? $count : '' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Leyenda para Mapa Inherente --}}
            <div class="heatmap-legend">
                <strong style="margin-right: 15px; font-size: 10px;">NIVELES:</strong>
                @foreach($allRiskLevels->reverse() as $level)
                    @php
                        $slug = \Illuminate\Support\Str::slug($level->title);
                    @endphp
                    <div class="legend-item risk-{{ $slug }}">
                        {{ strtoupper($level->title) }}
                    </div>
                @endforeach
            </div>

            <div class="page-break"></div>

            <h3>3.2 Mapa de Calor - Riesgo Residual</h3>
            <table class="heatmap">
                <thead>
                    <tr>
                        <th class="axis-label">IMPACTO →<br>PROBABILIDAD ↓</th>
                        @foreach($probabilities as $probability)
                            <th class="axis-label">
                                {{ $probability->title }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($impacts->reverse() as $impact)
                        <tr>
                            <th class="axis-label">{{ $impact->title }}</th>
                            @foreach($probabilities as $probability)
                                @php
                                    $count = $residualHeatmapData[$impact->id][$probability->id] ?? 0;
                                    $level = $matrixLevels[$impact->id][$probability->id] ?? null;
                                    $class = $level ? 'risk-' . \Illuminate\Support\Str::slug($level->title) : '';
                                @endphp
                                <td class="{{ $class }}">
                                    {{ $count > 0 ? $count : '' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Leyenda para Mapa Residual --}}
            <div class="heatmap-legend">
                <strong style="margin-right: 15px; font-size: 10px;">NIVELES:</strong>
                @foreach($allRiskLevels->reverse() as $level)
                    @php
                        $slug = \Illuminate\Support\Str::slug($level->title);
                    @endphp
                    <div class="legend-item risk-{{ $slug }}">
                        {{ strtoupper($level->title) }}
                    </div>
                @endforeach
            </div>

            <div class="page-break"></div>

            {{-- DISTRIBUCIÓN POR CATEGORÍA Y CONTEXTO --}}
            <h2>4. DISTRIBUCIÓN DE RIESGOS</h2>

            <div style="width: 48%; display: inline-block; vertical-align: top;">
                <h3>4.1 Por Categoría de Riesgo</h3>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>CATEGORÍA</th>
                            <th style="width: 80px;">CANTIDAD</th>
                            <th style="width: 80px;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risksByCategory->sortDesc() as $category => $count)
                            <tr>
                                <td>{{ $category }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $count }}</td>
                                <td style="text-align: center;">
                                    {{ $statistics['total_risks'] > 0 ? round(($count / $statistics['total_risks']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="width: 48%; display: inline-block; vertical-align: top; margin-left: 3%;">
                <h3>4.2 Por Contexto Estratégico</h3>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>CONTEXTO</th>
                            <th style="width: 80px;">CANTIDAD</th>
                            <th style="width: 80px;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($risksByContext->sortDesc() as $context => $count)
                            <tr>
                                <td>{{ $context }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $count }}</td>
                                <td style="text-align: center;">
                                    {{ $statistics['total_risks'] > 0 ? round(($count / $statistics['total_risks']) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="page-break"></div>

            {{-- DETALLE DE RIESGOS (Continuación del contenido original) --}}
            <h2>5. ANÁLISIS DETALLADO DE RIESGOS</h2>

            @php
                $groupedRisks = $risks->groupBy(function ($risk) {
                    return $risk->residualLevel?->title ?? 'Sin Clasificar';
                });
                $dynamicOrder = $allRiskLevels->reverse()->pluck('title')->toArray();
                $dynamicOrder[] = 'Sin Clasificar';
            @endphp

            @foreach ($dynamicOrder as $levelName)
                @php
                    $levelRisks = $groupedRisks->get($levelName, collect());
                @endphp
                
                @if ($levelRisks->isNotEmpty())
                    <h3 style="margin-top: 25px;">
                        5.{{ $loop->iteration }} RIESGOS NIVEL 
                        <span class="badge-{{ \Illuminate\Support\Str::slug($levelName) }}">
                            {{ strtoupper($levelName) }}
                        </span>
                        ({{ $levelRisks->count() }} {{ $levelRisks->count() == 1 ? 'RIESGO' : 'RIESGOS' }})
                    </h3>

                    @foreach ($levelRisks as $risk)
                        <div class="risk-block">
                            <div class="risk-header">
                                <span class="risk-code">{{ $risk->classification_code }}</span>
                                {{ strtoupper($risk->title) }}
                            </div>

                            {{-- Información General --}}
                            <table class="detail-table">
                                <tr>
                                    <td style="background-color: #ecf0f1; font-weight: bold; width: 25%;">Descripción</td>
                                    <td colspan="3">{{ $risk->description }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color: #ecf0f1; font-weight: bold;">Categoría</td>
                                    <td>{{ $risk->riskCategory?->title }}</td>
                                    <td style="background-color: #ecf0f1; font-weight: bold;">Contexto</td>
                                    <td>{{ $risk->strategicContext?->title }}</td>
                                </tr>
                                <tr>
                                    <td style="background-color: #ecf0f1; font-weight: bold;">Consecuencias</td>
                                    <td colspan="3">{{ $risk->consequences }}</td>
                                </tr>
                            </table>

                            {{-- Análisis de Riesgo --}}
                            <table class="detail-table" style="margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th colspan="3" style="background-color: #e74c3c;">RIESGO INHERENTE</th>
                                        <th colspan="3" style="background-color: #27ae60;">RIESGO RESIDUAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="background-color: #ecf0f1; font-weight: bold; font-size: 8px;">
                                        <td style="text-align: center;">Impacto</td>
                                        <td style="text-align: center;">Probabilidad</td>
                                        <td style="text-align: center;">Nivel</td>
                                        <td style="text-align: center;">Impacto</td>
                                        <td style="text-align: center;">Probabilidad</td>
                                        <td style="text-align: center;">Nivel</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;">{{ $risk->inherentImpact?->title }}</td>
                                        <td style="text-align: center;">{{ $risk->inherentProbability?->title }}</td>
                                        <td style="text-align: center;">
                                            <span class="badge-{{ \Illuminate\Support\Str::slug($risk->inherentLevel?->title) }}">
                                                {{ $risk->inherentLevel?->title }}
                                            </span>
                                        </td>
                                        <td style="text-align: center;">{{ $risk->residualImpact?->title }}</td>
                                        <td style="text-align: center;">{{ $risk->residualProbability?->title }}</td>
                                        <td style="text-align: center;">
                                            <span class="badge-{{ \Illuminate\Support\Str::slug($risk->residualLevel?->title) }}">
                                                {{ $risk->residualLevel?->title }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- Calificación de Controles --}}
                            @if($risk->controlGeneralQualificationCalculated)
                                <div class="section-box" style="margin-top: 10px;">
                                    <strong>Calificación General de Controles:</strong> 
                                    {{ $risk->controlGeneralQualificationCalculated->title }}
                                    (Factor de Reducción: {{ $risk->controlGeneralQualificationCalculated->reduction_factor }})
                                </div>
                            @endif

                            {{-- Causas y Controles --}}
                            @if($risk->potentialCauses->isNotEmpty())
                                <h4 style="margin-top: 15px; color: #e67e22;">CAUSAS POTENCIALES Y CONTROLES ASOCIADOS</h4>
                                @foreach($risk->potentialCauses as $cause)
                                    <div class="cause-item">
                                        <strong>Causa {{ $loop->iteration }}:</strong> {{ $cause->title }}
                                        
                                        @if($cause->controls->isNotEmpty())
                                            <div style="margin-top: 8px; margin-left: 15px;">
                                                <strong style="font-size: 9px; color: #2980b9;">Controles implementados:</strong>
                                                @foreach($cause->controls as $control)
                                                    <div class="control-item" style="margin-top: 5px;">
                                                        <strong>{{ $control->title }}</strong>
                                                        
                                                        <div style="margin-top: 5px; font-size: 8px;">
                                                            <strong>Contexto:</strong> 
                                                            @if($control->context_type === 'prevention')
                                                                <span style="color: #27ae60;">Prevención</span>
                                                            @else
                                                                <span style="color: #e67e22;">Materialización</span>
                                                            @endif
                                                            |
                                                            <strong>Tipo:</strong> {{ $control->controlType?->title ?? 'N/A' }} |
                                                            <strong>Periodicidad:</strong> {{ $control->periodicity?->title ?? 'N/A' }} |
                                                            <strong>Calificación:</strong> 
                                                            <span style="font-weight: bold; color: #2980b9;">
                                                                {{ $control->controlQualification?->title ?? 'N/A' }}
                                                            </span>
                                                            @if($control->controlQualification)
                                                                (Factor: {{ $control->controlQualification->reduction_factor }})
                                                            @endif
                                                        </div>
                                                        
                                                        @if($control->followUps && $control->followUps->count() > 0)
                                                            <div style="margin-top: 5px; font-size: 8px; color: #16a085;">
                                                                <strong>Seguimientos:</strong> {{ $control->followUps->count() }} registrado(s)
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div style="margin-top: 5px; color: #e74c3c; font-style: italic; font-size: 8px;">
                                                ⚠ No hay controles asociados a esta causa
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div style="margin-top: 10px; padding: 10px; background-color: #fff3cd; border-left: 3px solid #ffc107;">
                                    <strong>⚠ ATENCIÓN:</strong> No se han identificado causas potenciales para este riesgo.
                                </div>
                            @endif

                            {{-- Total de Controles --}}
                            @if($risk->controls->isNotEmpty())
                                <div style="margin-top: 15px; padding: 8px; background-color: #e8f4f8; border-left: 3px solid #2980b9;">
                                    <strong>Total de controles implementados:</strong> {{ $risk->controls->count() }}
                                </div>
                            @endif

                        </div>
                    @endforeach
                @endif
            @endforeach

            <div class="page-break"></div>

            {{-- PLANES DE ACCIÓN --}}
            <h2>6. PLANES DE ACCIÓN PROPUESTOS</h2>

            @php
                $risksWithActions = $risks->filter(fn($risk) => $risk->actions->isNotEmpty());
            @endphp

            @if($risksWithActions->isNotEmpty())
                <p style="margin-bottom: 15px; line-height: 1.6;">
                    Se han identificado <strong>{{ $statistics['total_actions'] }}</strong> acciones 
                    para <strong>{{ $statistics['risks_with_actions'] }}</strong> riesgos. 
                    A continuación se detallan las acciones propuestas para la mitigación y tratamiento de los riesgos:
                </p>

                <table class="detail-table">
                    <thead>
                        <tr>
                            <th style="width: 12%;">CÓDIGO RIESGO</th>
                            <th style="width: 25%;">RIESGO</th>
                            <th style="width: 28%;">ACCIÓN</th>
                            <th style="width: 15%;">RESPONSABLE</th>
                            <th style="width: 10%;">INICIO</th>
                            <th style="width: 10%;">FIN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dynamicOrder as $levelName)
                            @php
                                $levelRisks = $groupedRisks->get($levelName, collect())
                                    ->filter(fn($risk) => $risk->actions->isNotEmpty());
                            @endphp
                            
                            @foreach($levelRisks as $risk)
                                @foreach($risk->actions as $action)
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">
                                            {{ $risk->classification_code }}
                                        </td>
                                        <td>
                                            {{ $risk->title }}
                                            @if($loop->parent->first && $loop->first)
                                                <div style="margin-top: 3px;">
                                                    <span class="badge-{{ \Illuminate\Support\Str::slug($risk->residualLevel?->title) }}">
                                                        {{ $risk->residualLevel?->title }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $action->title }}</td>
                                        <td>{{ $action->responsibleBy?->name ?? 'No asignado' }}</td>
                                        <td style="text-align: center; font-size: 8px;">
                                            {{ $action->start_date ? $action->start_date->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td style="text-align: center; font-size: 8px;">
                                            {{ $action->end_date ? $action->end_date->format('d/m/Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                {{-- Análisis de Acciones por Nivel de Riesgo --}}
                <h3 style="margin-top: 20px;">6.1 Distribución de Acciones por Nivel de Riesgo</h3>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>NIVEL DE RIESGO</th>
                            <th style="width: 20%;">RIESGOS CON ACCIONES</th>
                            <th style="width: 20%;">TOTAL ACCIONES</th>
                            <th style="width: 20%;">PROMEDIO ACCIONES/RIESGO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dynamicOrder as $levelName)
                            @php
                                $levelRisksAll = $groupedRisks->get($levelName, collect());
                                $levelRisksWithActions = $levelRisksAll->filter(fn($r) => $r->actions->isNotEmpty());
                                $totalActions = $levelRisksAll->sum(fn($r) => $r->actions->count());
                                $avgActions = $levelRisksWithActions->count() > 0 
                                    ? round($totalActions / $levelRisksWithActions->count(), 1) 
                                    : 0;
                            @endphp
                            @if($levelRisksAll->isNotEmpty())
                                <tr>
                                    <td>
                                        <span class="badge-{{ \Illuminate\Support\Str::slug($levelName) }}">
                                            {{ strtoupper($levelName) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center; font-weight: bold;">
                                        {{ $levelRisksWithActions->count() }} / {{ $levelRisksAll->count() }}
                                    </td>
                                    <td style="text-align: center; font-weight: bold;">{{ $totalActions }}</td>
                                    <td style="text-align: center; font-weight: bold;">{{ $avgActions }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @else
                <div style="padding: 20px; background-color: #fff3cd; border: 2px solid #ffc107; text-align: center;">
                    <strong>⚠ ATENCIÓN:</strong> No se han definido planes de acción para los riesgos identificados.
                    @php
                        $highestLevels = $allRiskLevels->reverse()->take(2);
                        $criticalAndHighCount = 0;
                        $criticalAndHighNames = [];
                        foreach($highestLevels as $level) {
                            $count = $residualTotals[strtolower($level->title)] ?? 0;
                            $criticalAndHighCount += $count;
                            if($count > 0) {
                                $criticalAndHighNames[] = $level->title;
                            }
                        }
                    @endphp
                    @if($criticalAndHighCount > 0)
                        Se recomienda establecer acciones de tratamiento para los riesgos con nivel {{ implode(' y ', $criticalAndHighNames) }}.
                    @endif
                </div>
            @endif

            <div class="page-break"></div>

            {{-- CONCLUSIONES Y RECOMENDACIONES --}}
            <h2>7. CONCLUSIONES Y RECOMENDACIONES</h2>

            <h3>7.1 Conclusiones</h3>
            <div class="section-box">
                <ul style="margin: 0;">
                    <li>
                        Se identificaron <strong>{{ $statistics['total_risks'] }}</strong> riesgos distribuidos en:
                        @php
                            $distribution = [];
                            foreach($allRiskLevels->reverse() as $level) {
                                $count = $residualTotals[strtolower($level->title)] ?? 0;
                                if($count > 0) {
                                    $distribution[] = "<strong>{$count}</strong> " . ucfirst($level->title);
                                }
                            }
                        @endphp
                        {!! implode(', ', $distribution) !!}.
                    </li>
                    
                    <li>
                        Los controles implementados (<strong>{{ $statistics['total_controls'] }}</strong> en total) 
                        han logrado reducir la exposición al riesgo en un 
                        <strong>{{ $statistics['risk_reduction_percentage'] }}%</strong>, 
                        disminuyendo la puntuación total de riesgo de 
                        <strong>{{ $statistics['total_inherent_score'] }}</strong> puntos (inherente) a 
                        <strong>{{ $statistics['total_residual_score'] }}</strong> puntos (residual).
                        @if($statistics['risks_improved'] > 0)
                            Específicamente, <strong>{{ $statistics['risks_improved'] }}</strong> 
                            riesgo{{ $statistics['risks_improved'] != 1 ? 's' : '' }} 
                            mejoró{{ $statistics['risks_improved'] != 1 ? 'aron' : '' }} de nivel.
                        @endif
                    </li>

                    @php
                        $highestLevels = $allRiskLevels->reverse()->take(2);
                        $criticalAndHighCount = 0;
                        $criticalAndHighNames = [];
                        foreach($highestLevels as $level) {
                            $count = $residualTotals[strtolower($level->title)] ?? 0;
                            $criticalAndHighCount += $count;
                            if($count > 0) {
                                $criticalAndHighNames[] = $level->title;
                            }
                        }
                    @endphp
                    @if($criticalAndHighCount > 0)
                        <li>
                            Se identificaron <strong>{{ $criticalAndHighCount }}</strong> riesgos con nivel 
                            residual {{ implode(' o ', $criticalAndHighNames) }} que requieren atención prioritaria y planes de acción inmediatos.
                        </li>
                    @endif

                    @if($statistics['risks_with_actions'] > 0)
                        <li>
                            Se han propuesto <strong>{{ $statistics['total_actions'] }}</strong> acciones de tratamiento 
                            para <strong>{{ $statistics['risks_with_actions'] }}</strong> riesgos, lo que representa el 
                            {{ $statistics['total_risks'] > 0 ? round(($statistics['risks_with_actions'] / $statistics['total_risks']) * 100, 1) : 0 }}% 
                            del total de riesgos identificados.
                        </li>
                    @else
                        <li style="color: #e74c3c;">
                            <strong>⚠ CRÍTICO:</strong> No se han definido planes de acción para ninguno de los riesgos identificados. 
                            Es imperativo establecer acciones de mitigación.
                        </li>
                    @endif

                    <li>
                        El promedio de controles por riesgo es de <strong>{{ $statistics['avg_controls_per_risk'] }}</strong>, 
                        lo que indica 
                        @if($statistics['avg_controls_per_risk'] >= 3)
                            una cobertura robusta de controles.
                        @elseif($statistics['avg_controls_per_risk'] >= 2)
                            una cobertura adecuada de controles.
                        @else
                            una oportunidad de mejora en la implementación de controles.
                        @endif
                    </li>

                    @if($risksByCategory->count() > 0)
                        @php
                            $topCategory = $risksByCategory->sortDesc()->first();
                            $topCategoryName = $risksByCategory->sortDesc()->keys()->first();
                        @endphp
                        <li>
                            La categoría con mayor concentración de riesgos es 
                            <strong>"{{ $topCategoryName }}"</strong> con {{ $topCategory }} riesgo{{ $topCategory > 1 ? 's' : '' }}.
                        </li>
                    @endif
                </ul>
            </div>

            <h3 style="margin-top: 20px;">7.2 Recomendaciones</h3>
            <div class="section-box">
                <ol style="margin: 0;">
                    @if($criticalAndHighCount > 0)
                        <li>
                            <strong>Priorización de Riesgos:</strong> Enfocar los recursos en la gestión de los 
                            {{ $criticalAndHighCount }} riesgos con nivel {{ implode(' o ', $criticalAndHighNames) }}, 
                            estableciendo planes de acción con responsables y fechas definidas.
                        </li>
                    @endif

                    @if($statistics['risks_with_actions'] < $statistics['total_risks'])
                        <li>
                            <strong>Definición de Planes de Acción:</strong> Desarrollar acciones de tratamiento 
                            para los {{ $statistics['total_risks'] - $statistics['risks_with_actions'] }} riesgos 
                            que actualmente no cuentan con planes de mitigación.
                        </li>
                    @endif

                    <li>
                        <strong>Fortalecimiento de Controles:</strong> Revisar la efectividad de los controles 
                        existentes, especialmente para aquellos riesgos donde la reducción del nivel inherente 
                        al residual ha sido insuficiente.
                    </li>

                    <li>
                        <strong>Monitoreo Continuo:</strong> Establecer un proceso de revisión periódica 
                        (se recomienda trimestral) del perfil de riesgos para identificar cambios en el entorno, 
                        nuevos riesgos emergentes y validar la efectividad de los controles.
                    </li>

                    <li>
                        <strong>Capacitación y Cultura de Riesgo:</strong> Implementar programas de sensibilización 
                        y capacitación para el equipo del proceso sobre la gestión de riesgos y la importancia 
                        de los controles establecidos.
                    </li>

                    <li>
                        <strong>Documentación y Trazabilidad:</strong> Mantener actualizada la documentación 
                        de causas, controles y acciones, asegurando la trazabilidad de las decisiones tomadas 
                        en la gestión de riesgos.
                    </li>

                    <li>
                        <strong>Reporte de Materializaciones:</strong> Establecer un canal formal para reportar 
                        de manera oportuna cualquier materialización de riesgos, permitiendo la activación 
                        inmediata de planes de contingencia.
                    </li>

                    @if($statistics['avg_controls_per_risk'] < 2)
                        <li>
                            <strong>Reforzamiento de Controles:</strong> Dado que el promedio de controles por 
                            riesgo es bajo ({{ $statistics['avg_controls_per_risk'] }}), se recomienda evaluar 
                            la implementación de controles adicionales, especialmente de tipo preventivo.
                        </li>
                    @endif
                </ol>
            </div>

            <div class="page-break"></div>

            {{-- GLOSARIO --}}
            <h2>8. GLOSARIO DE TÉRMINOS</h2>
            <table class="info-table">
                <tr>
                    <td><strong>Riesgo</strong></td>
                    <td>Efecto de la incertidumbre sobre los objetivos. Puede ser positivo (oportunidad) o negativo (amenaza).</td>
                </tr>
                <tr>
                    <td><strong>Riesgo Inherente</strong></td>
                    <td>Nivel de riesgo antes de considerar los controles existentes. Se calcula multiplicando el impacto por la probabilidad originales del evento de riesgo.</td>
                </tr>
                <tr>
                    <td><strong>Riesgo Residual</strong></td>
                    <td>Nivel de riesgo que permanece después de implementar y considerar la efectividad de los controles. Representa la exposición real al riesgo.</td>
                </tr>
                <tr>
                    <td><strong>Impacto</strong></td>
                    <td>Magnitud de las consecuencias que puede generar la materialización de un riesgo sobre los objetivos del proceso.</td>
                </tr>
                <tr>
                    <td><strong>Probabilidad</strong></td>
                    <td>Posibilidad de que ocurra un evento de riesgo en un período de tiempo determinado.</td>
                </tr>
                <tr>
                    <td><strong>Control</strong></td>
                    <td>Medida, proceso o acción que modifica el riesgo. Puede ser preventivo (evita la ocurrencia), detectivo (identifica la ocurrencia) o correctivo (mitiga las consecuencias).</td>
                </tr>
                <tr>
                    <td><strong>Causa Potencial</strong></td>
                    <td>Evento, acción o condición que puede originar o contribuir a la materialización de un riesgo.</td>
                </tr>
                <tr>
                    <td><strong>Consecuencia</strong></td>
                    <td>Resultado de la materialización de un riesgo, que puede afectar los objetivos, procesos, recursos o la reputación de la organización.</td>
                </tr>
                <tr>
                    <td><strong>Plan de Acción</strong></td>
                    <td>Conjunto de actividades planificadas para tratar un riesgo, ya sea reduciendo su probabilidad, su impacto, o transfiriéndolo a terceros.</td>
                </tr>
                <tr>
                    <td><strong>Calificación del Control</strong></td>
                    <td>Evaluación de la efectividad de un control en la mitigación del riesgo, considerando su diseño, implementación y operación.</td>
                </tr>
                <tr>
                    <td><strong>Mapa de Calor (Heatmap)</strong></td>
                    <td>Representación visual matricial que muestra la distribución de riesgos según su impacto y probabilidad, facilitando la identificación de áreas críticas.</td>
                </tr>
                <tr>
                    <td><strong>Tratamiento del Riesgo</strong></td>
                    <td>Proceso de selección e implementación de medidas para modificar el riesgo. Incluye: evitar, reducir, transferir o aceptar el riesgo.</td>
                </tr>
            </table>

            <div class="page-break"></div>

            {{-- ANEXOS --}}
            <h2>9. ANEXOS</h2>

            <h3>9.1 Metodología de Evaluación</h3>
            <div class="section-box">
                <h4>Cálculo del Nivel de Riesgo:</h4>
                <p style="margin: 5px 0;">
                    <strong>Nivel de Riesgo = Impacto × Probabilidad</strong>
                </p>
                <p style="margin: 10px 0 5px 0;">
                    El resultado de esta multiplicación se clasifica según los siguientes rangos:
                </p>
                <ul style="margin: 5px 0;">
                    @foreach($allRiskLevels as $level)
                        <li>
                            <strong>{{ ucfirst($level->title) }}:</strong> 
                            {{ $level->min }} - {{ $level->max }} puntos
                        </li>
                    @endforeach
                </ul>
            </div>

            <h3 style="margin-top: 20px;">9.2 Escalas de Medición</h3>
            
            <div style="width: 48%; display: inline-block; vertical-align: top;">
                <h4>Escala de Impacto:</h4>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>NIVEL</th>
                            <th>PESO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($impacts->reverse() as $impact)
                            <tr>
                                <td>{{ $impact->title }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $impact->weight }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="width: 48%; display: inline-block; vertical-align: top; margin-left: 3%;">
                <h4>Escala de Probabilidad:</h4>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th>NIVEL</th>
                            <th>PESO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($probabilities->reverse() as $probability)
                            <tr>
                                <td>{{ $probability->title }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $probability->weight }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h3 style="margin-top: 25px;">9.3 Información del Documento</h3>
            <table class="info-table">
                <tr>
                    <td><strong>Versión del Documento</strong></td>
                    <td>1.0</td>
                </tr>
                <tr>
                    <td><strong>Fecha de Elaboración</strong></td>
                    <td>{{ now()->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td><strong>Elaborado por</strong></td>
                    <td>Sistema de Gestión de Riesgos</td>
                </tr>
                <tr>
                    <td><strong>Revisado por</strong></td>
                    <td>{{ $subprocess->leader->name ?? 'Pendiente' }}</td>
                </tr>
                <tr>
                    <td><strong>Clasificación</strong></td>
                    <td style="color: #e74c3c; font-weight: bold;">CONFIDENCIAL</td>
                </tr>
            </table>

            <div class="confidential-footer">
                <p>
                    Este documento contiene información confidencial y de uso exclusivo de la organización.<br>
                    Su distribución o reproducción no autorizada está prohibida.
                </p>
            </div>

        </div>
    </main>
</body>
</html>