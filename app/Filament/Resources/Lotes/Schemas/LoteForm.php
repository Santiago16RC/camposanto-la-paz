<?php

namespace App\Filament\Resources\Lotes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->label('Código')
                    ->required(),
                TextInput::make('seccion')
                    ->label('Sección')
                    ->required(),
                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'disponible' => 'Disponible',
                        'ocupado' => 'Ocupado',
                        'reservado' => 'Reservado',
                    ])
                    ->required()
                    ->default('disponible'),
                TextInput::make('titular_nombre')
                    ->label('Titular'),
                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull(),
            ]);
    }
}
