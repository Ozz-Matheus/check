<?php

namespace App\Filament\Resources\ActionResource\Forms;

use App\Models\Preventive;
use App\Models\SubProcess;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;

class PreventiveSchema
{
    public static function get(): array
    {
        return [

            Section::make('Action Preventive Data')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->required()
                        ->columnSpanFull(),
                    Select::make('process_id')
                        ->relationship('process', 'title')
                        ->afterStateUpdated(function (Set $set) {
                            $set('sub_process_id', null);
                            $set('responsible_by_id', null);
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Select::make('sub_process_id')
                        ->label('Sub Process')
                        ->options(
                            fn (Get $get): Collection => SubProcess::query()
                                ->where('process_id', $get('process_id'))
                                ->pluck('title', 'id')
                        )
                        ->afterStateUpdated(fn (Set $set) => $set('responsible_by_id', null))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Select::make('action_origin_id')
                        ->label('Origin')
                        ->relationship('origin', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('responsible_by_id')
                        ->label('Responsible')
                        ->options(
                            fn (Get $get): array => User::whereHas(
                                'subProcesses',
                                fn ($query) => $query->where('sub_process_id', $get('sub_process_id'))
                            )
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    DatePicker::make('detection_date')
                        ->format('Y-m-d')
                        ->required(),
                    Select::make('risk_probability')
                        ->label(__('Probability'))
                        ->options([
                            1 => 'Muy baja',
                            2 => 'Baja',
                            3 => 'Media',
                            4 => 'Alta',
                            5 => 'Muy alta',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get) {
                            $probability = $get('risk_probability');
                            $impact = $get('risk_impact');

                            $set('risk_evaluation', Preventive::evaluateRiskLevel($probability, $impact));
                        })
                        ->native(false),
                    Select::make('risk_impact')
                        ->label(__('Impact'))
                        ->options([
                            1 => 'Insignificante',
                            2 => 'Menor',
                            3 => 'Moderado',
                            4 => 'Mayor',
                            5 => 'Catastrófico',
                        ])
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($record, Set $set, Get $get) {
                            $probability = $get('risk_probability');
                            $impact = $get('risk_impact');

                            $set('risk_evaluation', Preventive::evaluateRiskLevel($probability, $impact));
                        })
                        ->native(false),
                    TextInput::make('risk_evaluation')
                        ->label(__('Risk evaluation'))
                        ->readOnly()
                        ->dehydrated(true),
                    Textarea::make('prevention_action')
                        ->required(),
                    Textarea::make('effectiveness_indicator')
                        ->required(),

                    Textarea::make('expected_impact')
                        ->required()
                        ->columnSpanFull(),
                    DatePicker::make('deadline')
                        ->minDate(now()->format('Y-m-d'))
                        ->required(),
                    TextInput::make('status_label')
                        ->label(__('Status'))
                        ->formatStateUsing(fn ($record) => $record?->status?->label ?? 'Sin estado')
                        ->disabled()
                        ->dehydrated(false)
                        ->visible(fn (string $context) => $context === 'view'),
                ]),

        ];
    }
}
