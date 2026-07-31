<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servicio extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'tipo_servicio_id',
    ];

    public function tipoServicio(): BelongsTo
    {
        return $this->belongsTo(TipoServicio::class);
    }
    public function ordenesTrabajo(): BelongsToMany
    {
        return $this->belongsToMany(
            OrdenTrabajo::class,
            'detalle_orden',
            'servicio_id',
            'orden_trabajo_id'
        )
            ->withPivot('precio')
            ->withTimestamps();
    }
}
