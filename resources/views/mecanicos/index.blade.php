@extends('layouts.app')

@section('title', 'Mecánicos')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">

        <h2>Mecánicos</h2>

        <a
            href="{{ route('mecanicos.create') }}"
            class="btn btn-primary">

            Nuevo Mecánico

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

    <table class="table table-bordered table-hover align-middle">

        <thead class="table-dark">

            <tr>
                <th>Nombre</th>
                <th>CI</th>
                <th>Teléfono</th>
                <th>Especialidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

            @forelse ($mecanicos as $mecanico)

                <tr>

                    <td>
                        {{ $mecanico->nombre }}
                        {{ $mecanico->apellido }}
                    </td>

                    <td>{{ $mecanico->ci ?? 'Sin CI' }}</td>

                    <td>
                        {{ $mecanico->telefono
                            ?? 'Sin teléfono' }}
                    </td>

                    <td>
                        {{ $mecanico->especialidad->nombre }}
                    </td>

                    <td>
                        <span class="badge
                            {{ $mecanico->estado === 'Activo'
                                ? 'text-bg-success'
                                : 'text-bg-secondary' }}">

                            {{ $mecanico->estado }}

                        </span>
                    </td>

                    <td>

                        <a
                            href="{{ route(
                                'mecanicos.edit',
                                $mecanico
                            ) }}"
                            class="btn btn-warning btn-sm">

                            Editar

                        </a>

                        <form
                            action="{{ route(
                                'mecanicos.destroy',
                                $mecanico
                            ) }}"
                            method="POST"
                            class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm"
                                onclick="return confirm(
                                    '¿Eliminar mecánico?'
                                )">

                                Eliminar

                            </button>

                        </form>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No existen mecánicos registrados.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection