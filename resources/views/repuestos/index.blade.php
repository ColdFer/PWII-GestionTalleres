@extends('layouts.app')

@section('title', 'Repuestos')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-3">

        <div>
            <h2>Repuestos e Inventario</h2>

            <p class="text-muted mb-0">
                Controle precios y existencias.
            </p>
        </div>

        <a
            href="{{ route('repuestos.create') }}"
            class="btn btn-primary">

            Nuevo Repuesto

        </a>

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
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Compra</th>
                    <th>Venta</th>
                    <th>Stock</th>
                    <th>Mínimo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($repuestos as $repuesto)

                    <tr class="{{
                        $repuesto->tieneStockBajo()
                            ? 'table-warning'
                            : ''
                    }}">

                        <td>{{ $repuesto->codigo }}</td>

                        <td>{{ $repuesto->nombre }}</td>

                        <td>
                            Bs {{ number_format(
                                $repuesto->precio_compra,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>
                            Bs {{ number_format(
                                $repuesto->precio_venta,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>

                            {{ $repuesto->stock }}

                            @if ($repuesto->tieneStockBajo())

                                <span
                                    class="badge text-bg-warning">

                                    Stock bajo

                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $repuesto->stock_minimo }}
                        </td>

                        <td>

                            <span class="badge
                                {{ $repuesto->estado
                                    === 'Activo'
                                    ? 'text-bg-success'
                                    : 'text-bg-secondary' }}">

                                {{ $repuesto->estado }}

                            </span>

                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'repuestos.edit',
                                    $repuesto
                                ) }}"
                                class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <form
                                action="{{ route(
                                    'repuestos.destroy',
                                    $repuesto
                                ) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        '¿Eliminar repuesto?'
                                    )">

                                    Eliminar

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center">

                            No existen repuestos registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection