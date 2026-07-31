<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Repuesto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'precio_compra' => 'decimal:2',
            'precio_venta' => 'decimal:2',
            'stock' => 'integer',
            'stock_minimo' => 'integer',
        ];
    }

    public function ordenesTrabajo(): BelongsToMany
    {
        return $this->belongsToMany(
            OrdenTrabajo::class,
            'detalle_repuesto_orden',
            'repuesto_id',
            'orden_trabajo_id'
        )
            ->withPivot([
                'cantidad',
                'precio_unitario',
                'subtotal',
            ])
            ->withTimestamps();
    }

    public function tieneStockBajo(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}