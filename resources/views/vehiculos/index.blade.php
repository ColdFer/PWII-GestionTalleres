@extends('layouts.app')

@section('title', 'Vehículos')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h2>Listado de Vehículos</h2>

        <a href="{{ route('vehiculos.create') }}"
           class="btn btn-primary">

            Nuevo Vehículo

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
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>Kilometraje</th>
                    <th>Propietario</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($vehiculos as $vehiculo)

                    <tr>

                        <td>{{ $vehiculo->id }}</td>

                        <td>{{ $vehiculo->placa }}</td>
                        <td>
                            {{ $vehiculo->modelo?->marca?->nombre
                                ?? 'Sin asignar' }}
                        </td>

                        <td>
                            {{ $vehiculo->modelo?->nombre
                                ?? 'Sin asignar' }}
                        </td>

                        <td>{{ $vehiculo->anio }}</td>

                        <td>{{ $vehiculo->color }}</td>

                        <td>
                            {{ number_format($vehiculo->kilometraje, 0, ',', '.') }} km
                        </td>

                        <td>
                            {{ $vehiculo->cliente->nombre }}
                            {{ $vehiculo->cliente->apellido }}
                        </td>

                        <td>

                            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}"
                               class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <form
                                action="{{ route('vehiculos.destroy', $vehiculo->id) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Desea eliminar este vehículo?')">

                                    Eliminar

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            No existen vehículos registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection