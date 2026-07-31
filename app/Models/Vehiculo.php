<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    protected $fillable = [

        'placa',
        'anio',
        'color',
        'kilometraje',
        'cliente_id',
        'modelo_id',
        
    ];
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }   

    public function ordenesTrabajo(): HasMany
    {
        return $this->hasMany(
            OrdenTrabajo::class,
            'vehiculo_id'
        );
    }
    public function modelo(): BelongsTo
    {
        return $this->belongsTo(
            ModeloVehiculo::class,
            'modelo_id'
        );
    }
}
