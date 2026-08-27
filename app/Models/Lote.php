<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['codigo', 'seccion', 'estado', 'titular_nombre', 'observaciones'])]
class Lote extends Model
{
    public function tramites(): HasMany
    {
        return $this->hasMany(Tramite::class);
    }
}
