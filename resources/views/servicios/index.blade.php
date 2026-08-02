@extends('layouts.app')

@section('title', 'Servicios')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Listado de Servicios</h2>

        <a href="{{ route('servicios.create') }}"
           class="btn btn-primary">

            Nuevo Servicio

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

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($servicios as $servicio)

                    <tr>

                        <td>{{ $servicio->id }}</td>

                        <td>{{ $servicio->nombre }}</td>

                        <td>
                            {{ $servicio->tipoServicio->nombre }}
                        </td>

                        <td>
                            {{ $servicio->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td>
                            Bs {{ number_format(
                                $servicio->precio,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'servicios.edit',
                                    $servicio
                                ) }}"
                                class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <form
                                action="{{ route(
                                    'servicios.destroy',
                                    $servicio
                                ) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        '¿Desea eliminar este servicio?'
                                    )">

                                    Eliminar

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            No existen servicios registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection