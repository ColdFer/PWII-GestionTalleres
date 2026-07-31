@extends('layouts.app')

@section('title', 'Editar rol')

@section('content')

<div class="container mt-4">

    <h2>Editar Rol</h2>

    <hr>

    @php
        $esAdministrador =
            $role->nombre === 'Administrador';

        $permisosSeleccionados = old(
            'permisos',
            $role->permisos->pluck('id')->all()
        );
    @endphp

    @if ($esAdministrador)

        <div class="alert alert-info">

            El rol Administrador mantiene todos los permisos
            y su nombre no puede cambiarse.

        </div>

    @endif

    <form
        action="{{ route('roles.update', $role) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label for="nombre" class="form-label">
                Nombre
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                maxlength="50"
                value="{{ old('nombre', $role->nombre) }}"
                class="form-control
                    @error('nombre') is-invalid @enderror"
                @readonly($esAdministrador)
                required>

            @error('nombre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="descripcion" class="form-label">
                Descripción
            </label>

            <input
                type="text"
                name="descripcion"
                id="descripcion"
                maxlength="150"
                value="{{ old(
                    'descripcion',
                    $role->descripcion
                ) }}"
                class="form-control
                    @error('descripcion') is-invalid @enderror">

            @error('descripcion')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label class="form-label">
                Permisos asignados
            </label>

            <div class="border rounded p-3">

                <div class="row">

                    @foreach ($permisos as $permiso)

                        <div class="col-md-6 mb-2">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="permisos[]"
                                    value="{{ $permiso->id }}"
                                    id="permiso{{ $permiso->id }}"
                                    class="form-check-input"
                                    @checked(
                                        in_array(
                                            $permiso->id,
                                            $permisosSeleccionados
                                        )
                                    )
                                    @disabled($esAdministrador)>

                                <label
                                    for="permiso{{ $permiso->id }}"
                                    class="form-check-label">

                                    <strong>
                                        {{ $permiso->nombre }}
                                    </strong>

                                    @if ($permiso->descripcion)

                                        <br>

                                        <small class="text-muted">
                                            {{ $permiso->descripcion }}
                                        </small>

                                    @endif

                                </label>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a
            href="{{ route('roles.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection