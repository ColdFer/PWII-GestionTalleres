@extends('layouts.app')

@section('title', 'Pagos')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>

            <h2>Pagos Registrados</h2>

            <p class="text-muted mb-0">
                Historial de cobros realizados.
            </p>

        </div>

        <div class="card shadow-sm">

            <div class="card-body py-2 px-4">

                <span class="text-muted">
                    Total cobrado:
                </span>

                <strong>
                    Bs {{ number_format(
                        $totalCobrado,
                        2,
                        ',',
                        '.'
                    ) }}
                </strong>

            </div>

        </div>

    </div>

    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    @if (session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif

    <div class="table-responsive">

        <table class="table table-bordered
                      table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th>Fecha</th>
                    <th>Orden</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Método</th>
                    <th>Referencia</th>
                    <th>Monto</th>
                    <th>Registrado por</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($pagos as $pago)

                    <tr>

                        <td>
                            {{ $pago->fecha->format('d/m/Y') }}
                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'ordenes.show',
                                    $pago->ordenTrabajo
                                ) }}">

                                {{ $pago
                                    ->ordenTrabajo
                                    ->codigo }}

                            </a>

                        </td>

                        <td>
                            {{ $pago
                                ->ordenTrabajo
                                ->vehiculo
                                ->cliente
                                ->nombre }}

                            {{ $pago
                                ->ordenTrabajo
                                ->vehiculo
                                ->cliente
                                ->apellido }}
                        </td>

                        <td>
                            {{ $pago
                                ->ordenTrabajo
                                ->vehiculo
                                ->placa }}
                        </td>

                        <td>
                            {{ $pago->metodo }}
                        </td>

                        <td>
                            {{ $pago->referencia
                                ?? 'Sin referencia' }}
                        </td>

                        <td>
                            Bs {{ number_format(
                                $pago->monto,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>
                            {{ $pago->registradoPor?->name
                                ?? 'Usuario no disponible' }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center">

                            No existen pagos registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection