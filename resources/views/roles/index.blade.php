@extends('layouts.app')

@section('title', 'Roles y permisos')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-3">

        <div>
            <h2>Roles y Permisos</h2>

            <p class="text-muted mb-0">
                Configure las funciones disponibles para cada rol.
            </p>
        </div>

        <a
            href="{{ route('roles.create') }}"
            class="btn btn-primary">

            Nuevo Rol

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
                    <th>Rol</th>
                    <th>Descripción</th>
                    <th>Usuarios</th>
                    <th>Permisos</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($roles as $role)

                    <tr>

                        <td>
                            <strong>{{ $role->nombre }}</strong>
                        </td>

                        <td>
                            {{ $role->descripcion
                                ?? 'Sin descripción' }}
                        </td>

                        <td>
                            {{ $role->usuarios_count }}
                        </td>

                        <td>

                            @forelse ($role->permisos as $permiso)

                                <span
                                    class="badge text-bg-secondary
                                           me-1 mb-1">

                                    {{ $permiso->nombre }}

                                </span>

                            @empty

                                <span class="text-muted">
                                    Sin permisos
                                </span>

                            @endforelse

                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'roles.edit',
                                    $role
                                ) }}"
                                class="btn btn-warning btn-sm">

                                Editar

                            </a>

                            <form
                                action="{{ route(
                                    'roles.destroy',
                                    $role
                                ) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        '¿Desea eliminar este rol?'
                                    )">

                                    Eliminar

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="text-center">

                            No existen roles registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection