<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(
            Permiso::class,
            'permiso_rol',
            'role_id',
            'permiso_id'
        );
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(
            User::class,
            'role_id'
        );
    }
}