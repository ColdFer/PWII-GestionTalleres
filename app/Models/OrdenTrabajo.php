<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdenTrabajo extends Model
{
    protected $table = 'ordenes_trabajo';

    protected $fillable = [
        'codigo',
        'vehiculo_id',
        'user_id',
        'fecha_ingreso',
        'fecha_salida_estimada',
        'estado',
        'diagnostico',
        'observaciones',
        'total',
        'mecanico_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'date',
            'fecha_salida_estimada' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function servicios(): BelongsToMany
    {
        return $this->belongsToMany(
            Servicio::class,
            'detalle_orden',
            'orden_trabajo_id',
            'servicio_id'
        )
            ->withPivot('precio')
            ->withTimestamps();
    }
    public function mecanico(): BelongsTo
    {
        return $this->belongsTo(
            Mecanico::class,
            'mecanico_id'
        );
    }
    public function repuestos(): BelongsToMany
    {
        return $this->belongsToMany(
            Repuesto::class,
            'detalle_repuesto_orden',
            'orden_trabajo_id',
            'repuesto_id'
        )
            ->withPivot([
                'cantidad',
                'precio_unitario',
                'subtotal',
            ])
            ->withTimestamps();
    }
    public function recalcularTotal(): void
    {
        $totalServicios = $this
            ->servicios()
            ->sum('detalle_orden.precio');

        $totalRepuestos = $this
            ->repuestos()
            ->sum('detalle_repuesto_orden.subtotal');

        $this->update([
            'total' => $totalServicios + $totalRepuestos,
        ]);
    }
    public function pagos(): HasMany
    {
        return $this->hasMany(
            Pago::class,
            'orden_trabajo_id'
        );
    }
    public function getTotalPagadoAttribute(): float
    {
        if ($this->relationLoaded('pagos')) {
            return round(
                (float) $this->pagos->sum('monto'),
                2
            );
        }

        return round(
            (float) $this->pagos()->sum('monto'),
            2
        );
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(
            0,
            round(
                (float) $this->total
                - $this->total_pagado,
                2
            )
        );
    }

    public function getEstadoPagoAttribute(): string
    {
        if ((float) $this->total <= 0) {
            return 'Sin monto';
        }

        if ($this->total_pagado <= 0) {
            return 'Pendiente';
        }

        if ($this->total_pagado < (float) $this->total) {
            return 'Parcial';
        }

        return 'Pagado';
    }
}