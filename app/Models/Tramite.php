<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['lote_id', 'tipo', 'estado', 'solicitante', 'fecha_solicitud', 'observaciones'])]
class Tramite extends Model
{
    protected function casts(): array
    {
        return [
            'fecha_solicitud' => 'date',
        ];
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class);
    }
}
