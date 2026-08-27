<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Correo verificado el'),
                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->label('Contraseña')
                    ->helperText('Déjalo en blanco para no cambiar la contraseña actual.'),
                Toggle::make('is_admin')
                    ->label('Administrador')
                    ->helperText('Solo los administradores pueden entrar a este panel.'),
            ]);
    }
}
