<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Acceso denegado</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-light">

    <div
        class="container d-flex justify-content-center
               align-items-center min-vh-100">

        <div class="card shadow-sm text-center p-4">

            <div class="card-body">

                <h1 class="display-4 text-danger">
                    403
                </h1>

                <h2 class="h4">
                    Acceso denegado
                </h2>

                <p class="text-muted">
                    {{ $exception->getMessage()
                        ?: 'No tiene permiso para acceder a esta sección.' }}
                </p>

                @auth

                    @if (auth()->user()->tieneRol('Cliente'))

                        <a
                            href="{{ route('cliente.dashboard') }}"
                            class="btn btn-primary">

                            Volver a mi portal

                        </a>

                    @else

                        <a
                            href="{{ route('dashboard') }}"
                            class="btn btn-primary">

                            Volver al dashboard

                        </a>

                    @endif

                @else

                    <a
                        href="{{ route('login') }}"
                        class="btn btn-primary">

                        Iniciar sesión

                    </a>

                @endauth

            </div>

        </div>

    </div>

</body>

</html>