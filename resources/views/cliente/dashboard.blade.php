@extends('layouts.cliente')

@section('title', 'Mi portal')

@section('content')

<div class="mb-4">

    <h1 class="h3">
        Bienvenido,
        {{ $cliente->nombre }}
        {{ $cliente->apellido }}
    </h1>

    <p class="text-muted">
        Consulte sus vehículos y el estado de sus reparaciones.
    </p>

</div>

<div class="row g-4 mb-4">

    <div class="col-md-4">

        <div class="card h-100 shadow-sm">

            <div class="card-body">

                <h2 class="h5">
                    🚗 Mis vehículos
                </h2>

                <div class="display-6 mb-3">
                    {{ $cliente->vehiculos_count }}
                </div>

                <a
                    href="{{ route(
                        'cliente.vehiculos.index'
                    ) }}"
                    class="btn btn-primary">

                    Ver vehículos

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card h-100 shadow-sm">

            <div class="card-body">

                <h2 class="h5">
                    🔧 Órdenes activas
                </h2>

                <div class="display-6 mb-3">
                    {{ $ordenesActivas }}
                </div>

                <a
                    href="{{ route(
                        'cliente.ordenes.index'
                    ) }}"
                    class="btn btn-primary">

                    Ver reparaciones

                </a>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card h-100 shadow-sm">

            <div class="card-body">

                <h2 class="h5">
                    📋 Historial total
                </h2>

                <div class="display-6 mb-3">
                    {{ $totalOrdenes }}
                </div>

                <a
                    href="{{ route(
                        'cliente.ordenes.index'
                    ) }}"
                    class="btn btn-primary">

                    Ver historial

                </a>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm">

    <div class="card-header">
        Reparaciones recientes
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>Código</th>
                        <th>Vehículo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse ($ordenesRecientes as $orden)

                        <tr>

                            <td>
                                {{ $orden->codigo }}
                            </td>

                            <td>
                                {{ $orden->vehiculo->placa }}
                            </td>

                            <td>
                                {{ $orden->fecha_ingreso
                                    ->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $orden->estado }}
                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'cliente.ordenes.show',
                                        $orden
                                    ) }}"
                                    class="btn btn-primary btn-sm">

                                    Ver

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center">

                                No existen reparaciones registradas.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection