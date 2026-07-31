<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Portal del cliente')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

        <div class="container">

            <a
                href="{{ route('cliente.dashboard') }}"
                class="navbar-brand">

                🚗 Portal del Cliente

            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarCliente"
                aria-controls="navbarCliente"
                aria-expanded="false"
                aria-label="Mostrar menú">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div
                class="collapse navbar-collapse"
                id="navbarCliente">

                <ul class="navbar-nav me-auto">

                    <li class="nav-item">

                        <a
                            href="{{ route('cliente.dashboard') }}"
                            class="nav-link">
                            Mi portal
                        </a>

                    </li>

                    <li class="nav-item">

                        <a
                            href="{{ route('cliente.vehiculos.index') }}"
                            class="nav-link">
                            Mis vehículos
                        </a>

                    </li>

                    <li class="nav-item">
                        <a
                            href="{{ route('cliente.ordenes.index') }}"
                            class="nav-link">
                            Mis reparaciones
                        </a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-3 text-white">

                    <span>
                        {{ auth()->user()->name }}
                    </span>

                    <form
                        action="{{ route('logout') }}"
                        method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-light btn-sm">

                            Cerrar sesión

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </nav>

    <main class="container py-4">

        @yield('content')

    </main>

</body>

</html>