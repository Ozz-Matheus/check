<x-filament::widget>
    <x-filament::card>
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Mapa de Calor de Riesgos</h2>
            </div>

            <!-- Heatmap Grid -->
            <div class="overflow-x-auto">
                <div class="inline-block w-full align-middle">
                    <div class="relative">
                        <div class="ml-8">
                            <table class="w-full border-collapse" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th class="w-32"></th>
                                        @foreach ($impacts as $impact)
                                            <th class="p-3 text-center font-semibold text-gray-700 dark:text-white text-sm pb-2 min-w-[110px]">
                                                {{ $impact->title }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($probs as $prob)
                                        <tr>
                                            <th class="text-right px-3 font-semibold text-gray-700 dark:text-white text-sm whitespace-nowrap">
                                                {{ $prob->title }}
                                            </th>
                                            @foreach ($impacts as $impact)
                                                @php
                                                    $cell = $cells[$prob->title][$impact->title] ?? null;
                                                    $hasRisks = $cell['count'] > 0;
                                                @endphp
                                                <td class="p-0.5">
                                                    <div class=" bg-gray-50 dark:bg-white/5 relative group h-16 transition-all duration-200 hover:scale-105 hover:shadow-lg cursor-pointer border-t border-gray-200 dark:border-white/10"
                                                         style="{{ $cell['color'] ? 'background-color: ' . $cell['color'] . ';' : '' }}">
                                                        @if($hasRisks)
                                                            <div class="flex items-center justify-center h-full">
                                                                <div class="bg-gray-900/80 text-white  w-8 h-8 flex items-center justify-center font-bold text-sm ">
                                                                    {{ $cell['count'] }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            @php
                $riskLevels = [
                    ['label' => 'Bajo', 'color' => config('filament-colors.success.hex')],
                    ['label' => 'Medio', 'color' => config('filament-colors.yellow.hex')],
                    ['label' => 'Alto', 'color' => config('filament-colors.warning.hex')],
                    ['label' => 'Muy alto', 'color' => config('filament-colors.danger.hex')],
                ];
            @endphp
            <div class="flex flex-wrap items-center justify-start gap-x-6 gap-y-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <span class="text-sm font-medium text-gray-700 dark:text-white">Niveles de Riesgo:</span>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                    @foreach ($riskLevels as $level)
                        <div class="flex items-center gap-2">
                            <span class="w-4 h-4 rounded-full" style="background-color: {{ $level['color'] }};"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-white">{{ $level['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>