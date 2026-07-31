<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(
            Role::class,
            'role_id'
        );
    }

    public function tieneRol(string $nombreRol): bool
    {
        return $this->rol?->nombre === $nombreRol;
    }

    public function tienePermiso(string $nombrePermiso): bool
    {
        if (!$this->rol) {
            return false;
        }

        return $this->rol
            ->permisos()
            ->where('nombre', $nombrePermiso)
            ->exists();
    }
    public function cliente(): HasOne
    {
        return $this->hasOne(
            Cliente::class,
            'user_id'
        );
    }
    public function ordenesCreadas(): HasMany
    {
        return $this->hasMany(
            OrdenTrabajo::class,
            'user_id'
        );
    }
    public function pagosRegistrados(): HasMany
    {
        return $this->hasMany(
            Pago::class,
            'user_id'
        );
    }
}