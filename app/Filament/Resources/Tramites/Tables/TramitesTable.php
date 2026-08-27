<?php

namespace App\Filament\Resources\Tramites\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TramitesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lote.codigo')
                    ->label('Lote')
                    ->searchable(),
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->searchable(),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completado' => 'success',
                        'en_proceso' => 'warning',
                        'pendiente' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('solicitante')
                    ->label('Solicitante')
                    ->searchable(),
                TextColumn::make('fecha_solicitud')
                    ->label('Fecha de solicitud')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
