<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

    <div class="container-fluid">

        <a
            class="navbar-brand fw-bold"
            href="{{ route('dashboard') }}">

            🚗 Gestión Talleres

        </a>

        <div class="ms-auto d-flex align-items-center gap-3 text-white">

            <span>

                Bienvenido,

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <span class="badge text-bg-light ms-1">

                    {{ auth()->user()->rol?->nombre ?? 'Sin rol' }}

                </span>

            </span>

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="m-0">

                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-light">

                    Cerrar sesión

                </button>

            </form>

        </div>

    </div>

</nav>