<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Crear roles
        |--------------------------------------------------------------------------
        */

        $administrador = Role::updateOrCreate(
            ['nombre' => 'Administrador'],
            [
                'descripcion' =>
                    'Acceso completo a todos los módulos del sistema.',
            ]
        );

        $empleado = Role::updateOrCreate(
            ['nombre' => 'Empleado'],
            [
                'descripcion' =>
                    'Gestiona clientes, vehículos, servicios y órdenes.',
            ]
        );

        $cliente = Role::updateOrCreate(
            ['nombre' => 'Cliente'],
            [
                'descripcion' =>
                    'Consulta sus vehículos, órdenes y reparaciones.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Crear permisos
        |--------------------------------------------------------------------------
        */

        $permisos = [
            [
                'nombre' => 'panel.administrativo',
                'descripcion' => 'Acceder al panel administrativo.',
            ],
            [
                'nombre' => 'usuarios.gestionar',
                'descripcion' => 'Administrar usuarios del sistema.',
            ],
            [
                'nombre' => 'roles.gestionar',
                'descripcion' => 'Administrar roles y permisos.',
            ],
            [
                'nombre' => 'clientes.gestionar',
                'descripcion' => 'Registrar, editar y consultar clientes.',
            ],
            [
                'nombre' => 'vehiculos.gestionar',
                'descripcion' => 'Registrar, editar y consultar vehículos.',
            ],
            [
                'nombre' => 'tipos_servicios.gestionar',
                'descripcion' => 'Administrar tipos de servicio.',
            ],
            [
                'nombre' => 'servicios.gestionar',
                'descripcion' => 'Administrar servicios del taller.',
            ],
            [
                'nombre' => 'especialidades.gestionar',
                'descripcion' => 'Administrar especialidades.',
            ],
            [
                'nombre' => 'mecanicos.gestionar',
                'descripcion' => 'Administrar mecánicos.',
            ],
            [
                'nombre' => 'ordenes.gestionar',
                'descripcion' => 'Administrar órdenes de trabajo.',
            ],
            [
                'nombre' => 'inventario.gestionar',
                'descripcion' => 'Administrar repuestos e inventario.',
            ],
            [
                'nombre' => 'pagos.gestionar',
                'descripcion' => 'Administrar pagos y facturación.',
            ],
            [
                'nombre' => 'reportes.ver',
                'descripcion' => 'Consultar reportes administrativos.',
            ],
            [
                'nombre' => 'portal_cliente.ver',
                'descripcion' => 'Ingresar al portal del cliente.',
            ],
            [
                'nombre' => 'mis_vehiculos.ver',
                'descripcion' => 'Consultar los vehículos propios.',
            ],
            [
                'nombre' => 'mis_ordenes.ver',
                'descripcion' => 'Consultar las órdenes propias.',
            ],
            [
                'nombre' => 'mis_facturas.ver',
                'descripcion' => 'Consultar las facturas propias.',
            ],
        ];

        $permisosCreados = collect();

        foreach ($permisos as $datosPermiso) {
            $permiso = Permiso::updateOrCreate(
                ['nombre' => $datosPermiso['nombre']],
                ['descripcion' => $datosPermiso['descripcion']]
            );

            $permisosCreados->push($permiso);
        }

        /*
        |--------------------------------------------------------------------------
        | Permisos del administrador
        |--------------------------------------------------------------------------
        |
        | El administrador recibe todos los permisos.
        |
        */

        $administrador->permisos()->sync(
            $permisosCreados->pluck('id')->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Permisos del empleado
        |--------------------------------------------------------------------------
        */

        $permisosEmpleado = $permisosCreados
            ->whereIn('nombre', [
                'panel.administrativo',
                'clientes.gestionar',
                'vehiculos.gestionar',
                'tipos_servicios.gestionar',
                'servicios.gestionar',
                'especialidades.gestionar',
                'mecanicos.gestionar',
                'ordenes.gestionar',
                'inventario.gestionar',
                'pagos.gestionar',
            ])
            ->pluck('id')
            ->all();

        $empleado->permisos()->sync($permisosEmpleado);

        /*
        |--------------------------------------------------------------------------
        | Permisos del cliente
        |--------------------------------------------------------------------------
        */

        $permisosCliente = $permisosCreados
            ->whereIn('nombre', [
                'portal_cliente.ver',
                'mis_vehiculos.ver',
                'mis_ordenes.ver',
                'mis_facturas.ver',
            ])
            ->pluck('id')
            ->all();

        $cliente->permisos()->sync($permisosCliente);

        /*
        |--------------------------------------------------------------------------
        | Asignar rol administrador a usuarios existentes sin rol
        |--------------------------------------------------------------------------
        */

        User::whereNull('role_id')->update([
            'role_id' => $administrador->id,
        ]);
    }
}