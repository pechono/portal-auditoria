<div>
    @if (!$grupo)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-yellow-700 font-medium">Todavía no estás asignado a ningún grupo.</p>
            <p class="text-yellow-600 text-sm mt-1">Esperá que el docente te asigne un grupo y un caso.</p>
        </div>
    @elseif($grupo->estado === 'finalizado')
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-10 text-center">
            <div class="text-5xl mb-4">🎓</div>
            <h2 class="text-2xl font-bold text-green-700 mb-2">¡Felicitaciones!</h2>
            <p class="text-green-600 text-lg font-medium mb-1">{{ $grupo->nombre }}</p>
            <p class="text-green-500 text-sm mb-4">{{ $grupo->caso->nombre }}</p>
            <p class="text-gray-600 text-sm max-w-md mx-auto">
                Completaron exitosamente el ciclo de auditoría. El docente ha cerrado el grupo. ¡Muy buen trabajo!
            </p>
            @php $alumno_pivot = $grupo->usuarios->firstWhere('id', auth()->id())?->pivot; @endphp
            @if($alumno_pivot && $alumno_pivot->condicion)
                <div class="mt-6 inline-block px-6 py-3 rounded-full text-sm font-semibold
                    {{ $alumno_pivot->condicion === 'promocionado' ? 'bg-green-600 text-white' : ($alumno_pivot->condicion === 'regular' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white') }}">
                    Condición: {{ ucfirst($alumno_pivot->condicion) }}
                </div>
            @endif
            @if($grupo->devolucion_final_path)
                <div class="mt-6">
                    <a href="{{ asset('uploads/' . $grupo->devolucion_final_path) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-green-700 border-2 border-green-500 rounded-lg font-semibold text-sm hover:bg-green-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                        Descargar devolución final
                    </a>
                    <p class="text-xs text-gray-500 mt-2">El docente subió la corrección del trabajo grupal.</p>
                </div>
            @endif
        </div>

    @else
        {{-- Info del caso --}}
        <div class="bg-indigo-50 rounded-lg p-6 mb-6">
            <p class="text-indigo-700 font-semibold text-lg">{{ $grupo->nombre }}</p>
            <p class="text-indigo-600 text-sm mt-1">{{ $grupo->caso->nombre }}</p>
            <p class="text-indigo-400 text-xs mt-1">{{ $grupo->caso->descripcion }}</p>
        </div>

        {{-- Manual del alumno --}}
        <div class="mb-6 bg-white rounded-lg shadow p-4 flex justify-between items-center">
            <div>
                <p class="text-sm font-medium text-gray-800">Manual del alumno</p>
                <p class="text-xs text-gray-400 mt-0.5">Guia de uso del portal — Version 1.0</p>
            </div>
            <a href="{{ asset('docs/manual-alumno.pdf') }}" target="_blank"
                class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Descargar manual
            </a>
        </div>

        {{-- Próxima etapa --}}
        @if ($proxima_etapa)
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-indigo-500">
                <p class="text-xs text-gray-400 uppercase font-medium">Próxima acción</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">
                    Etapa {{ $proxima_etapa->numero }}: {{ $proxima_etapa->nombre }}
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $proxima_etapa->descripcion }}</p>
                <a href="{{ route('alumno.etapas') }}"
                    class="inline-block mt-3 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Ir a mis etapas →
                </a>
            </div>
        @else
            <div class="bg-green-50 rounded-lg p-6 mb-6 border-l-4 border-green-500">
                <p class="text-green-700 font-semibold">¡Todas las etapas completadas!</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Progreso de etapas --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-800">Progreso</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($etapas as $etapa)
                        @php $entrega = $entregas->get($etapa->id) @endphp
                        <div class="px-6 py-3 flex justify-between items-center">
                            <p class="text-sm text-gray-700">{{ $etapa->numero }}. {{ $etapa->nombre }}</p>
                            @if ($etapa->numero === 1)
                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Informativa</span>
                            @elseif (!$entrega)
                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-500 rounded-full">Pendiente</span>
                            @elseif ($entrega->estado === 'aprobada')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">✓ Aprobada</span>
                            @elseif ($entrega->estado === 'enviada')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">En revisión</span>
                            @elseif ($entrega->estado === 'con_observaciones')
                                <span class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded-full">Con observaciones</span>
                            @elseif ($entrega->estado === 'rechazada')
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Rechazada</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Solicitudes recientes --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800">Mis solicitudes recientes</h3>
                    <a href="{{ route('alumno.recursos') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($solicitudes as $solicitud)
                        <div class="px-6 py-3 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-700 truncate max-w-xs">
                                    {{ Str::limit($solicitud->justificacion, 50) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $solicitud->created_at->format('d/m/Y') }}</p>
                            </div>
                            @if ($solicitud->estado === 'pendiente')
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Pendiente</span>
                            @elseif ($solicitud->estado === 'aprobada')
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Aprobada</span>
                            @elseif ($solicitud->estado === 'rechazada')
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Rechazada</span>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-400">No hay solicitudes todavía.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Notificaciones --}}
        @php
            $notificaciones = \App\Models\Notificacion::where('user_id', auth()->id())
                ->where('leida', false)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        @endphp

        @if ($notificaciones->count() > 0)
            <div class="mt-6 bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800">Notificaciones</h3>
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                        {{ $notificaciones->count() }} sin leer
                    </span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach ($notificaciones as $notif)
                        <div class="px-6 py-3 flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-700">{{ $notif->mensaje }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            <form method="POST" action="{{ route('alumno.notificaciones.leer', $notif->id) }}">
                                @csrf
                                <button type="submit" class="text-xs text-indigo-600 hover:underline ml-4 shrink-0">
                                    Marcar leída
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @endif
</div>