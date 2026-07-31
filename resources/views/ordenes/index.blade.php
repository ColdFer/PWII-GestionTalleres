@extends('layouts.app')

@section('title', 'Órdenes de trabajo')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-3">

        <h2>Órdenes de Trabajo</h2>

        <a
            href="{{ route('ordenes.create') }}"
            class="btn btn-primary">

            Nueva Orden

        </a>

    </div>

    @if (session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <div class="table-responsive">

        <table class="table table-bordered
                      table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Ingreso</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                    <th>Mecánico</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($ordenes as $orden)

                    <tr>

                        <td>
                            <strong>{{ $orden->codigo }}</strong>
                        </td>

                        <td>
                            {{ $orden->vehiculo->cliente->nombre }}
                            {{ $orden->vehiculo->cliente->apellido }}
                        </td>

                        <td>{{ $orden->vehiculo->placa }}</td>
                        <td>

                            <form
                                action="{{ route(
                                    'ordenes.mecanico',
                                    $orden
                                ) }}"
                                method="POST"
                                class="d-flex gap-2">

                                @csrf
                                @method('PATCH')

                                <select
                                    name="mecanico_id"
                                    class="form-select form-select-sm">

                                    <option value="">
                                        Sin asignar
                                    </option>

                                    @foreach ($mecanicos as $mecanico)

                                        <option
                                            value="{{ $mecanico->id }}"
                                            @selected(
                                                $orden->mecanico_id
                                                == $mecanico->id
                                            )>

                                            {{ $mecanico->nombre }}
                                            {{ $mecanico->apellido }}

                                        </option>

                                    @endforeach

                                </select>

                                <button class="btn btn-primary btn-sm">
                                    Asignar
                                </button>

                            </form>

                        </td>

                        <td>
                            {{ $orden->fecha_ingreso->format('d/m/Y') }}
                        </td>

                        <td>

                            <form
                                action="{{ route(
                                    'ordenes.estado',
                                    $orden
                                ) }}"
                                method="POST"
                                class="d-flex gap-2">

                                @csrf
                                @method('PATCH')

                                <select
                                    name="estado"
                                    class="form-select form-select-sm">

                                    @foreach ([
                                        'Pendiente',
                                        'En diagnóstico',
                                        'En reparación',
                                        'Finalizada',
                                        'Entregada',
                                        'Cancelada',
                                    ] as $estado)

                                        <option
                                            value="{{ $estado }}"
                                            @selected(
                                                $orden->estado === $estado
                                            )>

                                            {{ $estado }}

                                        </option>

                                    @endforeach

                                </select>

                                <button
                                    type="submit"
                                    class="btn btn-success btn-sm">

                                    Guardar

                                </button>

                            </form>

                        </td>

                        <td>
                            Bs {{ number_format(
                                $orden->total,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'ordenes.show',
                                    $orden
                                ) }}"
                                class="btn btn-info btn-sm">

                                Ver

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="text-center">

                            No existen órdenes registradas.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection