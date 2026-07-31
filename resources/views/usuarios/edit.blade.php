@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')

<div class="container mt-4">

    <h2>Editar Usuario</h2>

    <hr>

    <form
        action="{{ route('usuarios.update', $usuario) }}"
        method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label for="name" class="form-label">
                Nombre completo
            </label>

            <input
                type="text"
                name="name"
                id="name"
                maxlength="255"
                value="{{ old('name', $usuario->name) }}"
                class="form-control
                    @error('name') is-invalid @enderror"
                required>

            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="email" class="form-label">
                Correo de acceso
            </label>

            <input
                type="email"
                name="email"
                id="email"
                maxlength="255"
                value="{{ old('email', $usuario->email) }}"
                class="form-control
                    @error('email') is-invalid @enderror"
                required>

            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="role_id" class="form-label">
                Rol
            </label>

            <select
                name="role_id"
                id="role_id"
                class="form-select
                    @error('role_id') is-invalid @enderror"
                required>

                <option value="">
                    Seleccione un rol
                </option>

                @foreach ($roles as $rol)

                    <option
                        value="{{ $rol->id }}"
                        @selected(
                            old(
                                'role_id',
                                $usuario->role_id
                            ) == $rol->id
                        )>

                        {{ $rol->nombre }}

                    </option>

                @endforeach

            </select>

            @error('role_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label for="password" class="form-label">
                Nueva contraseña
            </label>

            <input
                type="password"
                name="password"
                id="password"
                minlength="8"
                placeholder="Vacío para conservar la actual"
                class="form-control
                    @error('password') is-invalid @enderror">

            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="mb-3">

            <label
                for="password_confirmation"
                class="form-label">

                Confirmar nueva contraseña

            </label>

            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                minlength="8"
                placeholder="Repita la nueva contraseña"
                class="form-control
                    @error('password') is-invalid @enderror">

        </div>

        <button type="submit" class="btn btn-success">
            Actualizar
        </button>

        <a
            href="{{ route('usuarios.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection