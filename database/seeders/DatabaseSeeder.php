<?php

namespace Database\Seeders;

use App\Models\Lote;
use App\Models\Tramite;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@camposantolapaz.test'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'is_admin' => true,
            ]
        );

        $loteA = Lote::updateOrCreate(
            ['codigo' => 'A-101'],
            ['seccion' => 'Sección A', 'estado' => 'ocupado', 'titular_nombre' => 'Familia Rodríguez']
        );

        Lote::updateOrCreate(
            ['codigo' => 'A-102'],
            ['seccion' => 'Sección A', 'estado' => 'disponible']
        );

        Lote::updateOrCreate(
            ['codigo' => 'B-204'],
            ['seccion' => 'Sección B', 'estado' => 'reservado', 'titular_nombre' => 'Familia Gómez']
        );

        Tramite::updateOrCreate(
            ['lote_id' => $loteA->id, 'tipo' => 'mantenimiento', 'solicitante' => 'Familia Rodríguez'],
            ['estado' => 'pendiente', 'fecha_solicitud' => now()]
        );
    }
}
