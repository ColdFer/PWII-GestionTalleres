<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\PortalClienteController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CatalogoVehiculoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;

/*
|--------------------------------------------------------------------------
| Entrada principal
|--------------------------------------------------------------------------
|
| No tendremos una página pública. Al entrar al dominio, el usuario será
| enviado directamente al formulario de inicio de sesión.
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rutas para invitados
|--------------------------------------------------------------------------
|
| Solo pueden acceder los usuarios que todavía no iniciaron sesión.
|
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.authenticate');

/*
|--------------------------------------------------------------------------
| Rutas para usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


Route::get(
    '/reportes',
    [ReporteController::class, 'index']
)
    ->middleware('permiso:reportes.ver')
    ->name('reportes.index');
    
Route::get(
    '/pagos',
    [PagoController::class, 'index']
)
    ->middleware('permiso:pagos.gestionar')
    ->name('pagos.index');

Route::post(
    '/ordenes/{ordenTrabajo}/pagos',
    [PagoController::class, 'store']
)
    ->middleware('permiso:pagos.gestionar')
    ->name('ordenes.pagos.store');

Route::delete(
    '/ordenes/{ordenTrabajo}/pagos/{pago}',
    [PagoController::class, 'destroy']
)
    ->middleware('permiso:pagos.gestionar')
    ->name('ordenes.pagos.destroy');


Route::resource(
    'repuestos',
    RepuestoController::class
)
    ->except(['show'])
    ->middleware('permiso:inventario.gestionar');

Route::post(
    '/ordenes/{ordenTrabajo}/repuestos',
    [OrdenTrabajoController::class, 'agregarRepuesto']
)
    ->middleware('permiso:ordenes.gestionar')
    ->name('ordenes.repuestos.store');

Route::delete(
    '/ordenes/{ordenTrabajo}/repuestos/{repuesto}',
    [OrdenTrabajoController::class, 'quitarRepuesto']
)
    ->middleware('permiso:ordenes.gestionar')
    ->name('ordenes.repuestos.destroy');   

Route::resource(
    'especialidades',
    EspecialidadController::class
)
    ->parameters([
        'especialidades' => 'especialidad',
    ])
    ->except([
        'show',
        'create',
    ])
    ->middleware('permiso:especialidades.gestionar');

Route::resource(
    'mecanicos',
    MecanicoController::class
)
    ->except(['show'])
    ->middleware('permiso:mecanicos.gestionar');


Route::patch(
    '/ordenes/{ordenTrabajo}/mecanico',
    [OrdenTrabajoController::class, 'actualizarMecanico']
)
    ->middleware('permiso:ordenes.gestionar')
    ->name('ordenes.mecanico');
    
    
Route::resource('roles', RoleController::class)
    ->except(['show'])
    ->middleware('permiso:roles.gestionar');

Route::get(
    '/catalogos-vehiculos',
    [CatalogoVehiculoController::class, 'index']
)
    ->middleware('permiso:vehiculos.gestionar')
    ->name('catalogos-vehiculos.index');

Route::post(
    '/marcas',
    [CatalogoVehiculoController::class, 'guardarMarca']
)
    ->middleware('permiso:vehiculos.gestionar')
    ->name('marcas.store');

Route::post(
    '/modelos-vehiculos',
    [CatalogoVehiculoController::class, 'guardarModelo']
)
    ->middleware('permiso:vehiculos.gestionar')
    ->name('modelos-vehiculos.store');
    
Route::resource('usuarios', UsuarioController::class)
    ->except(['show'])
    ->middleware('permiso:usuarios.gestionar');
    /*
    |--------------------------------------------------------------------------
    | Cerrar sesión
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboard administrativo
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )
        ->middleware('permiso:panel.administrativo')
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Clientes
    |--------------------------------------------------------------------------
    */

    Route::resource('clientes', ClienteController::class)
        ->middleware('permiso:clientes.gestionar');

    /*
    |--------------------------------------------------------------------------
    | Vehículos
    |--------------------------------------------------------------------------
    */

    Route::resource('vehiculos', VehiculoController::class)
        ->middleware('permiso:vehiculos.gestionar');

    /*
    |--------------------------------------------------------------------------
    | Tipos de servicio
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'tipos-servicios',
        TipoServicioController::class
    )
        ->parameters([
            'tipos-servicios' => 'tipoServicio',
        ])
        ->except(['show'])
        ->middleware('permiso:tipos_servicios.gestionar');

    /*
    |--------------------------------------------------------------------------
    | Servicios
    |--------------------------------------------------------------------------
    */
    
    Route::resource('servicios', ServicioController::class)
        ->except(['show'])
        ->middleware('permiso:servicios.gestionar');



    Route::resource(
        'ordenes',
        OrdenTrabajoController::class
    )
        ->parameters([
            'ordenes' => 'ordenTrabajo',
        ])
        ->only([
            'index',
            'create',
            'store',
            'show',
        ])
        ->middleware('permiso:ordenes.gestionar');

    Route::patch(
        '/ordenes/{ordenTrabajo}/estado',
        [OrdenTrabajoController::class, 'actualizarEstado']
    )
        ->middleware('permiso:ordenes.gestionar')
        ->name('ordenes.estado');
    /*
    |--------------------------------------------------------------------------
    | Portal del cliente
    |--------------------------------------------------------------------------
    */

    Route::prefix('portal')
    ->name('cliente.')
    ->middleware('permiso:portal_cliente.ver')
    ->group(function () {

        Route::get(
            '/',
            [PortalClienteController::class, 'dashboard']
        )->name('dashboard');

        Route::get(
            '/vehiculos',
            [PortalClienteController::class, 'vehiculos']
        )
            ->middleware('permiso:mis_vehiculos.ver')
            ->name('vehiculos.index');

        Route::get(
            '/ordenes',
            [PortalClienteController::class, 'ordenes']
        )
            ->middleware('permiso:mis_ordenes.ver')
            ->name('ordenes.index');

        Route::get(
            '/ordenes/{ordenTrabajo}',
            [PortalClienteController::class, 'mostrarOrden']
        )
            ->middleware('permiso:mis_ordenes.ver')
            ->name('ordenes.show');
    });
});