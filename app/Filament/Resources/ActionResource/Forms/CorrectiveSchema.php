<?php

namespace App\Filament\Resources\ActionResource\Forms;

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

class CorrectiveSchema
{
    public static function get(): array
    {
        return [
            Section::make('Action Data')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label(__('Title'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label(__('Description'))
                        ->required()
                        ->columnSpanFull(),
                    Select::make('process_id')
                        ->label(__('Process'))
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
                        ->label(__('Sub Process'))
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
                        ->label(__('Origin'))
                        ->relationship('origin', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('responsible_by_id')
                        ->label(__('Responsible'))
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
                        ->label(__('Detection date'))
                        ->format('Y-m-d')
                        ->required(),
                    Textarea::make('containment_action')
                        ->label(__('Containment action'))
                        ->columnSpanFull()
                        ->required(),
                    Select::make('action_analysis_cause_id')
                        ->label(__('Analysis cause'))
                        ->relationship('analysisCause', 'title')
                        ->required()
                        ->native(false),
                    Textarea::make('corrective_action')
                        ->label(__('Corrective action'))
                        ->columnSpanFull()
                        ->required(),
                    Select::make('action_verification_method_id')
                        ->label(__('Verification method'))
                        ->relationship('verificationMethod', 'title')
                        ->required()
                        ->native(false),
                    Select::make('verification_responsible_by_id')
                        ->label(__('Verification responsible'))
                        ->relationship('verificationResponsible', 'name')
                        ->required()
                        ->native(false),
                    DatePicker::make('deadline')
                        ->label(__('Deadline'))
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
