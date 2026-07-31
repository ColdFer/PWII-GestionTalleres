@extends('layouts.app')

@section('title', 'Tipos de servicio')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Tipos de Servicio</h2>

        <a href="{{ route('tipos-servicios.create') }}"
           class="btn btn-primary">

            Nuevo Tipo

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
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($tiposServicios as $tipoServicio)

                    <tr>

                        <td>{{ $tipoServicio->id }}</td>

                        <td>{{ $tipoServicio->nombre }}</td>

                        <td>
                            {{ $tipoServicio->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'tipos-servicios.edit',
                                    $tipoServicio
                                ) }}"
                                class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <form
                                action="{{ route(
                                    'tipos-servicios.destroy',
                                    $tipoServicio
                                ) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        '¿Desea eliminar este tipo de servicio?'
                                    )">

                                    Eliminar

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center">

                            No existen tipos de servicio registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection