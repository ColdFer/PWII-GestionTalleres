<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {
        $nombre = config('admin.name');
        $correo = config('admin.email');
        $contrasena = config('admin.password');

        if (empty($correo) || empty($contrasena)) {
            throw new RuntimeException(
                'Debe configurar ADMIN_EMAIL y ADMIN_PASSWORD en el archivo .env.'
            );
        }

        $rolAdministrador = Role::where(
            'nombre',
            'Administrador'
        )->first();

        if (!$rolAdministrador) {
            throw new RuntimeException(
                'No se encontró el rol Administrador. Ejecute primero RolesPermisosSeeder.'
            );
        }

        User::updateOrCreate(
            [
                'email' => $correo,
            ],
            [
                'name' => $nombre,
                'password' => Hash::make($contrasena),
                'role_id' => $rolAdministrador->id,
            ]
        );
    }
}