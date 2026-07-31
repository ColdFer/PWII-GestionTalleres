@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between
                align-items-center mb-3">

        <h2>Usuarios del Sistema</h2>

        <a
            href="{{ route('usuarios.create') }}"
            class="btn btn-primary">

            Nuevo Usuario

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
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Tipo de cuenta</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                @forelse ($usuarios as $usuario)

                    <tr>

                        <td>{{ $usuario->id }}</td>

                        <td>{{ $usuario->name }}</td>

                        <td>{{ $usuario->email }}</td>

                        <td>

                            <span class="badge text-bg-primary">

                                {{ $usuario->rol?->nombre
                                    ?? 'Sin rol' }}

                            </span>

                        </td>

                        <td>

                            @if ($usuario->cliente)

                                <span class="badge text-bg-success">
                                    Cliente
                                </span>

                            @else

                                <span class="badge text-bg-secondary">
                                    Personal
                                </span>

                            @endif

                        </td>

                        <td>

                            @if ($usuario->cliente)

                                <a
                                    href="{{ route(
                                        'clientes.edit',
                                        $usuario->cliente
                                    ) }}"
                                    class="btn btn-info btn-sm">

                                    Ver cliente

                                </a>

                            @else

                                <a
                                    href="{{ route(
                                        'usuarios.edit',
                                        $usuario
                                    ) }}"
                                    class="btn btn-warning btn-sm">

                                    Editar

                                </a>

                            @endif

                            <form
                                action="{{ route(
                                    'usuarios.destroy',
                                    $usuario
                                ) }}"
                                method="POST"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm(
                                        '¿Desea eliminar este usuario?'
                                    )">

                                    Eliminar

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center">

                            No existen usuarios registrados.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection