<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->rol === 'docente' ? route('docente.dashboard') : route('alumno.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->rol === 'docente')
                        @php
                            $sol_pendientes  = \App\Models\Solicitud::where('estado', 'pendiente')->count();
                            $ent_pendientes  = \App\Models\Entrega::where('estado', 'enviada')->count();
                        @endphp
                        <x-nav-link :href="route('docente.dashboard')" :active="request()->routeIs('docente.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('docente.alumnos')" :active="request()->routeIs('docente.alumnos')">
                            Alumnos
                        </x-nav-link>
                        <x-nav-link :href="route('docente.grupos')" :active="request()->routeIs('docente.grupos')">
                            Grupos
                        </x-nav-link>
                        <x-nav-link :href="route('docente.solicitudes')" :active="request()->routeIs('docente.solicitudes')">
                            <span class="flex items-center gap-1.5">
                                Solicitudes
                                @if($sol_pendientes > 0)
                                    <span class="px-1.5 py-0.5 text-xs bg-yellow-500 text-white rounded-full leading-none">
                                        {{ $sol_pendientes }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>
                        <x-nav-link :href="route('docente.entregas')" :active="request()->routeIs('docente.entregas')">
                            <span class="flex items-center gap-1.5">
                                Entregas
                                @if($ent_pendientes > 0)
                                    <span class="px-1.5 py-0.5 text-xs bg-yellow-500 text-white rounded-full leading-none">
                                        {{ $ent_pendientes }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>
                        <x-nav-link :href="route('docente.casos')" :active="request()->routeIs('docente.casos')">
                            Casos
                        </x-nav-link>
                        <x-nav-link :href="route('docente.repositorio')" :active="request()->routeIs('docente.repositorio')">
                            Repositorio
                        </x-nav-link>
                        <x-nav-link :href="route('docente.finales')" :active="request()->routeIs('docente.finales')">
                            Finales
                        </x-nav-link>
                    @else
                        @php
                            $notif_solicitudes = \App\Models\Notificacion::where('user_id', auth()->id())
                                ->where('leida', false)
                                ->where('tipo', 'like', 'solicitud_%')
                                ->count();
                            $notif_entregas = \App\Models\Notificacion::where('user_id', auth()->id())
                                ->where('leida', false)
                                ->where('tipo', 'like', 'entrega_%')
                                ->count();
                        @endphp
                        <x-nav-link :href="route('alumno.dashboard')" :active="request()->routeIs('alumno.dashboard')">
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('alumno.etapas')" :active="request()->routeIs('alumno.etapas')">
                            <span class="flex items-center gap-1.5">
                                Mis etapas
                                @if($notif_entregas > 0)
                                    <span class="px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full leading-none">
                                        {{ $notif_entregas }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>
                        <x-nav-link :href="route('alumno.recursos')" :active="request()->routeIs('alumno.recursos')">
                            <span class="flex items-center gap-1.5">
                                Recursos
                                @if($notif_solicitudes > 0)
                                    <span class="px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full leading-none">
                                        {{ $notif_solicitudes }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ auth()->user()->nombre_completo }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->rol === 'docente')
                <x-responsive-nav-link :href="route('docente.dashboard')" :active="request()->routeIs('docente.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.alumnos')" :active="request()->routeIs('docente.alumnos')">
                    Alumnos
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.grupos')" :active="request()->routeIs('docente.grupos')">
                    Grupos
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.solicitudes')" :active="request()->routeIs('docente.solicitudes')">
                    <span class="flex items-center gap-1.5">
                        Solicitudes
                        @if($sol_pendientes > 0)
                            <span class="px-1.5 py-0.5 text-xs bg-yellow-500 text-white rounded-full leading-none">
                                {{ $sol_pendientes }}
                            </span>
                        @endif
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.entregas')" :active="request()->routeIs('docente.entregas')">
                    <span class="flex items-center gap-1.5">
                        Entregas
                        @if($ent_pendientes > 0)
                            <span class="px-1.5 py-0.5 text-xs bg-yellow-500 text-white rounded-full leading-none">
                                {{ $ent_pendientes }}
                            </span>
                        @endif
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.casos')" :active="request()->routeIs('docente.casos')">
                    Casos
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.repositorio')" :active="request()->routeIs('docente.repositorio')">
                    Repositorio
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docente.finales')" :active="request()->routeIs('docente.finales')">
                    Finales
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('alumno.dashboard')" :active="request()->routeIs('alumno.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('alumno.etapas')" :active="request()->routeIs('alumno.etapas')">
                    <span class="flex items-center gap-1.5">
                        Mis etapas
                        @if($notif_entregas > 0)
                            <span class="px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full leading-none">
                                {{ $notif_entregas }}
                            </span>
                        @endif
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('alumno.recursos')" :active="request()->routeIs('alumno.recursos')">
                    <span class="flex items-center gap-1.5">
                        Recursos
                        @if($notif_solicitudes > 0)
                            <span class="px-1.5 py-0.5 text-xs bg-red-500 text-white rounded-full leading-none">
                                {{ $notif_solicitudes }}
                            </span>
                        @endif
                    </span>
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ auth()->user()->nombre_completo }}</div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>