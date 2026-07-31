@extends('layouts.app')

@section('title', 'Registrar usuario')

@section('content')

<div class="container mt-4">

    <h2>Registrar Usuario</h2>

    <p class="text-muted">
        Este formulario crea cuentas para administradores
        y empleados del taller.
    </p>

    <hr>

    <form
        action="{{ route('usuarios.store') }}"
        method="POST">

        @csrf

        <div class="mb-3">

            <label for="name" class="form-label">
                Nombre completo
            </label>

            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                maxlength="255"
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
                value="{{ old('email') }}"
                maxlength="255"
                placeholder="usuario@taller.com"
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
                            old('role_id') == $rol->id
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
                Contraseña
            </label>

            <input
                type="password"
                name="password"
                id="password"
                minlength="8"
                placeholder="Mínimo 8 caracteres"
                class="form-control
                    @error('password') is-invalid @enderror"
                required>

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

                Confirmar contraseña

            </label>

            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                minlength="8"
                placeholder="Repita la contraseña"
                class="form-control
                    @error('password') is-invalid @enderror"
                required>

        </div>

        <button type="submit" class="btn btn-success">
            Guardar
        </button>

        <a
            href="{{ route('usuarios.index') }}"
            class="btn btn-secondary">

            Cancelar

        </a>

    </form>

</div>

@endsection