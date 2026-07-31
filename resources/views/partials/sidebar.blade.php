<div class="bg-dark text-white vh-100 p-3" style="width:260px;">

    <h4 class="text-center mb-4">

        Menú Principal

    </h4>
    @php
        $usuarioActual = auth()->user();
    @endphp
    <hr>

    <ul class="nav flex-column">

        @if ($usuarioActual->tienePermiso('panel.administrativo'))

            <li class="nav-item">
                <a
                    href="{{ route('dashboard') }}"
                    class="nav-link text-white">
                    🏠 Dashboard
                </a>
            </li>

        @endif

        @if ($usuarioActual->tienePermiso('usuarios.gestionar'))

            <li class="nav-item">

                <a
                    href="{{ route('usuarios.index') }}"
                    class="nav-link text-white">
                    👤 Usuarios
                </a>

            </li>

        @endif

        @if ($usuarioActual->tienePermiso('roles.gestionar'))

            <li class="nav-item">

                <a
                    href="{{ route('roles.index') }}"
                    class="nav-link text-white">

                    🔐 Roles y permisos

                </a>

            </li>

        @endif

        @if ($usuarioActual->tienePermiso('clientes.gestionar'))

            <li class="nav-item">
                <a
                    href="{{ route('clientes.index') }}"
                    class="nav-link text-white">
                    👥 Clientes
                </a>
            </li>

        @endif

        @if ($usuarioActual->tienePermiso('vehiculos.gestionar'))

            <li class="nav-item">
                <a
                    href="{{ route('vehiculos.index') }}"
                    class="nav-link text-white">
                    🚗 Vehículos
                </a>
            </li>
        @endif

        @if ($usuarioActual->tienePermiso('vehiculos.gestionar'))

            <li class="nav-item">

                <a
                    href="{{ route('catalogos-vehiculos.index') }}"
                    class="nav-link text-white">

                    🏷️ Marcas y modelos

                </a>

            </li>

        @endif

       @if ($usuarioActual->tienePermiso('especialidades.gestionar'))

            <li class="nav-item">
                <a
                    href="{{ route('especialidades.index') }}"
                    class="nav-link text-white">

                    🧰 Especialidades

                </a>
            </li>

        @endif

        @if ($usuarioActual->tienePermiso('mecanicos.gestionar'))

            <li class="nav-item">
                <a
                    href="{{ route('mecanicos.index') }}"
                    class="nav-link text-white">

                    👨‍🔧 Mecánicos

                </a>
            </li>

        @endif

        @if ($usuarioActual->tienePermiso('tipos_servicios.gestionar')|| $usuarioActual->tienePermiso('servicios.gestionar'))

            <li class="nav-item">
                <a
                    class="nav-link text-white
                        d-flex justify-content-between
                        align-items-center"
                    data-bs-toggle="collapse"
                    href="#submenuServicios"
                    role="button"
                    aria-expanded="false"
                    aria-controls="submenuServicios">
                    <span>🛠 Servicios</span>
                    <span>▾</span>
                </a>
                <div
                    class="collapse"
                    id="submenuServicios">
                    <ul class="nav flex-column ms-3">
                        @if (
                            $usuarioActual->tienePermiso(
                                'tipos_servicios.gestionar'
                            )
                        )
                            <li class="nav-item">
                                <a
                                    href="{{ route(
                                        'tipos-servicios.index'
                                    ) }}"
                                    class="nav-link text-white">
                                    🧰 Tipos de servicio
                                </a>
                            </li>
                        @endif
                        @if (
                            $usuarioActual->tienePermiso(
                                'servicios.gestionar'
                            )
                        )
                            <li class="nav-item">
                                <a
                                    href="{{ route('servicios.index') }}"
                                    class="nav-link text-white">
                                    🔧 Servicios
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif
        @if ($usuarioActual->tienePermiso('ordenes.gestionar'))

            <li class="nav-item">

                <a
                    href="{{ route('ordenes.index') }}"
                    class="nav-link text-white">

                    📋 Órdenes de trabajo

                </a>

            </li>

        @endif
        @if ($usuarioActual->tienePermiso('inventario.gestionar'))

            <li class="nav-item">

                <a
                    href="{{ route('repuestos.index') }}"
                    class="nav-link text-white">

                    📦 Repuestos e inventario

                </a>

            </li>

        @endif


        @if ($usuarioActual->tienePermiso('pagos.gestionar'))

            <li class="nav-item">
                <a
                    href="{{ route('pagos.index') }}"
                    class="nav-link text-white">
                    💳 Pagos
                </a>
            </li>

        @endif

        @if ($usuarioActual->tienePermiso('reportes.ver'))

            <li class="nav-item">

                <a
                    href="{{ route('reportes.index') }}"
                    class="nav-link text-white">
                    📊 Reportes
                </a>
            </li>

        @endif

    </ul>

</div>