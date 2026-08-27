<?php

namespace App\Filament\Resources\Tramites\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TramiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lote_id')
                    ->relationship('lote', 'codigo')
                    ->searchable()
                    ->preload()
                    ->label('Lote'),
                Select::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'traspaso' => 'Traspaso',
                        'inhumacion' => 'Inhumación',
                        'exhumacion' => 'Exhumación',
                        'mantenimiento' => 'Pago de mantenimiento',
                    ])
                    ->required(),
                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'en_proceso' => 'En proceso',
                        'completado' => 'Completado',
                    ])
                    ->required()
                    ->default('pendiente'),
                TextInput::make('solicitante')
                    ->label('Solicitante')
                    ->required(),
                DatePicker::make('fecha_solicitud')
                    ->label('Fecha de solicitud')
                    ->required(),
                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),
            ]);
    }
}
