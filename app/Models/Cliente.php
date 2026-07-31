<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $fillable = [

        'user_id',
        'nombre',
        'apellido',
        'ci',
        'telefono',
        'correo',
        'direccion'
        
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class);
    }
}
