<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Controles superiores --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">

        {{-- Filtro por estado --}}
        <div class="flex gap-2">
            <button wire:click="$set('filtro_estado', 'pendiente')"
                class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'pendiente' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border' }}">
                Pendientes
            </button>
            <button wire:click="$set('filtro_estado', 'aprobada')"
                class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'aprobada' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border' }}">
                Aprobadas
            </button>
            <button wire:click="$set('filtro_estado', 'rechazada')"
                class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'rechazada' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 border' }}">
                Rechazadas
            </button>
        </div>

        {{-- Toggle de vista --}}
        <div class="flex gap-1 bg-gray-100 p-1 rounded-lg">
            <button wire:click="cambiarVista('grupos')"
                title="Vista por grupos"
                class="flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-md transition
                    {{ $vista === 'grupos' ? 'bg-white shadow text-indigo-700 font-medium' : 'text-gray-500 hover:text-gray-700' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Por grupo
            </button>
            <button wire:click="cambiarVista('lista')"
                title="Vista lista"
                class="flex items-center gap-1.5 px-3 py-1.5 text-sm rounded-md transition
                    {{ $vista === 'lista' ? 'bg-white shadow text-indigo-700 font-medium' : 'text-gray-500 hover:text-gray-700' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Lista
            </button>
        </div>
    </div>

    {{-- Barra de búsqueda y filtro de tipo --}}
    <div class="flex flex-wrap gap-3 mb-6">
        {{-- Buscar por nombre de grupo --}}
        <div class="relative flex-1 min-w-[200px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="busqueda"
                type="text"
                placeholder="Buscar por nombre de grupo..."
                class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        {{-- Filtro por tipo --}}
        <div class="flex gap-2">
            <button wire:click="$set('filtro_tipo', '')"
                class="px-3 py-2 text-sm rounded-lg {{ $filtro_tipo === '' ? 'bg-gray-700 text-white' : 'bg-white text-gray-700 border' }}">
                Todos
            </button>
            <button wire:click="$set('filtro_tipo', 'documento')"
                class="px-3 py-2 text-sm rounded-lg {{ $filtro_tipo === 'documento' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border' }}">
                Documentos
            </button>
            <button wire:click="$set('filtro_tipo', 'entrevista')"
                class="px-3 py-2 text-sm rounded-lg {{ $filtro_tipo === 'entrevista' ? 'bg-purple-600 text-white' : 'bg-white text-gray-700 border' }}">
                Entrevistas
            </button>
        </div>

        {{-- Limpiar filtros --}}
        @if($busqueda !== '' || $filtro_tipo !== '')
            <button wire:click="limpiarFiltros"
                class="flex items-center gap-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 bg-white border rounded-lg hover:bg-gray-50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
        @endif
    </div>

    {{-- ==================== VISTA POR GRUPOS ==================== --}}
    @if ($vista === 'grupos')

        @if ($grupos->isEmpty())
            <div class="bg-white rounded-lg shadow p-10 text-center text-gray-500 text-sm">
                @if($busqueda !== '' || $filtro_tipo !== '')
                    No hay resultados para los filtros aplicados.
                    <button wire:click="limpiarFiltros" class="ml-1 text-indigo-600 hover:underline">Limpiar filtros</button>
                @else
                    No hay solicitudes {{ $filtro_estado === 'pendiente' ? 'pendientes' : $filtro_estado . 's' }}.
                @endif
            </div>
        @else
            <div class="space-y-6">
                @foreach ($grupos as $grupo)
                    <div class="bg-white rounded-xl shadow overflow-hidden">

                        {{-- Cabecera del grupo --}}
                        <div class="flex items-center justify-between px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                            <div>
                                <h3 class="text-base font-semibold text-indigo-800">{{ $grupo->nombre }}</h3>
                                <p class="text-xs text-indigo-500 mt-0.5">{{ $grupo->caso->nombre }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                {{-- Alumnos del grupo --}}
                                @foreach ($grupo->usuarios as $alumno)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs bg-white border border-indigo-200 text-indigo-700 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $alumno->nombre }} {{ $alumno->apellido }}
                                    </span>
                                @endforeach
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                    {{ $filtro_estado === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : ($filtro_estado === 'aprobada' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $grupo->solicitudes->count() }} solicitud{{ $grupo->solicitudes->count() !== 1 ? 'es' : '' }}
                                </span>
                            </div>
                        </div>

                        {{-- Solicitudes del grupo --}}
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-2 text-left text-xs font-medium text-gray-400 uppercase">Alumno</th>
                                    <th class="px-6 py-2 text-left text-xs font-medium text-gray-400 uppercase">Tipo</th>
                                    <th class="px-6 py-2 text-left text-xs font-medium text-gray-400 uppercase">Justificación</th>
                                    <th class="px-6 py-2 text-left text-xs font-medium text-gray-400 uppercase">Fecha</th>
                                    <th class="px-6 py-2 text-left text-xs font-medium text-gray-400 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($grupo->solicitudes as $solicitud)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 text-sm text-gray-700">
                                            {{ $solicitud->solicitante->nombre_completo }}
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $solicitud->tipo === 'documento' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                                {{ ucfirst($solicitud->tipo) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-sm text-gray-500 max-w-xs">
                                            <p class="truncate">{{ $solicitud->justificacion }}</p>
                                            <button
                                                onclick="document.getElementById('just-g-{{ $solicitud->id }}').classList.toggle('hidden')"
                                                class="text-xs text-indigo-600 hover:underline mt-1">
                                                Ver completo
                                            </button>
                                            <p id="just-g-{{ $solicitud->id }}" class="hidden text-xs text-gray-600 mt-2 whitespace-pre-wrap">
                                                {{ $solicitud->justificacion }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-3 text-xs text-gray-400">
                                            {{ $solicitud->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-3">
                                            @if ($solicitud->estado === 'pendiente')
                                                <div class="flex gap-2">
                                                    <button wire:click="abrirModal({{ $solicitud->id }}, 'aprobar')"
                                                        class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                                        Aprobar
                                                    </button>
                                                    <button wire:click="abrirModal({{ $solicitud->id }}, 'rechazar')"
                                                        class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                                        Rechazar
                                                    </button>
                                                </div>
                                            @else
                                                <div class="flex flex-col gap-1">
                                                    <span class="text-xs text-gray-500">{{ $solicitud->comentario_docente ?? '—' }}</span>
                                                    @if ($solicitud->estado === 'aprobada' && $solicitud->tipo === 'entrevista')
                                                        @php $ent = \App\Models\Entrevistado::find($solicitud->recurso_id) @endphp
                                                        @if ($ent && $ent->acta_path)
                                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Acta asignada</span>
                                                        @else
                                                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Sin acta</span>
                                                        @endif
                                                    @elseif ($solicitud->estado === 'aprobada' && $solicitud->tipo === 'documento')
                                                        @php $doc = \App\Models\Documento::find($solicitud->recurso_id) @endphp
                                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded" title="{{ $doc?->titulo }}">
                                                            ✓ {{ $doc ? $doc->codigo . ' — ' . \Str::limit($doc->titulo, 30) : 'Documento entregado' }}
                                                        </span>
                                                    @elseif ($solicitud->estado === 'rechazada')
                                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Rechazada</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>

            {{-- Paginación (grupos) --}}
            <div class="mt-6">
                {{ $grupos->links() }}
            </div>
        @endif

    {{-- ==================== VISTA LISTA ==================== --}}
    @else

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo / Caso</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Justificación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($solicitudes as $solicitud)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $solicitud->grupo->nombre }}</p>
                                <p class="text-xs text-gray-400">{{ $solicitud->grupo->caso->nombre }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $solicitud->solicitante->nombre_completo }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $solicitud->tipo === 'documento' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ ucfirst($solicitud->tipo) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                <p class="truncate">{{ $solicitud->justificacion }}</p>
                                <button
                                    onclick="document.getElementById('just-{{ $solicitud->id }}').classList.toggle('hidden')"
                                    class="text-xs text-indigo-600 hover:underline mt-1">
                                    Ver completo
                                </button>
                                <p id="just-{{ $solicitud->id }}" class="hidden text-xs text-gray-600 mt-2 whitespace-pre-wrap">
                                    {{ $solicitud->justificacion }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                {{ $solicitud->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($solicitud->estado === 'pendiente')
                                    <div class="flex gap-2">
                                        <button wire:click="abrirModal({{ $solicitud->id }}, 'aprobar')"
                                            class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                            Aprobar
                                        </button>
                                        <button wire:click="abrirModal({{ $solicitud->id }}, 'rechazar')"
                                            class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                            Rechazar
                                        </button>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs text-gray-500">{{ $solicitud->comentario_docente ?? '—' }}</span>
                                        @if ($solicitud->estado === 'aprobada' && $solicitud->tipo === 'entrevista')
                                            @php $ent = \App\Models\Entrevistado::find($solicitud->recurso_id) @endphp
                                            @if ($ent && $ent->acta_path)
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Acta asignada</span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Sin acta</span>
                                            @endif
                                        @elseif ($solicitud->estado === 'aprobada' && $solicitud->tipo === 'documento')
                                            @php $doc = \App\Models\Documento::find($solicitud->recurso_id) @endphp
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded" title="{{ $doc?->titulo }}">
                                                ✓ {{ $doc ? $doc->codigo . ' — ' . \Str::limit($doc->titulo, 30) : 'Documento entregado' }}
                                            </span>
                                        @elseif ($solicitud->estado === 'rechazada')
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Rechazada</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                @if($busqueda !== '' || $filtro_tipo !== '')
                                    No hay resultados para los filtros aplicados.
                                    <button wire:click="limpiarFiltros" class="ml-1 text-indigo-600 hover:underline">Limpiar filtros</button>
                                @else
                                    No hay solicitudes {{ $filtro_estado === 'pendiente' ? 'pendientes' : $filtro_estado . 's' }}.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación (lista) --}}
        @if ($solicitudes->hasPages())
            <div class="mt-4">
                {{ $solicitudes->links() }}
            </div>
        @endif

    @endif

    {{-- ==================== MODAL ==================== --}}
    @if ($mostrarModal)
        @php
            $sol = \App\Models\Solicitud::with(['grupo.caso', 'grupo.usuarios', 'solicitante'])->find($solicitud_id);
        @endphp
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg flex flex-col max-h-[90vh]">

                {{-- Encabezado del modal con color según acción --}}
                <div class="px-6 py-4 rounded-t-xl flex items-center justify-between
                    {{ $accion === 'aprobar' ? 'bg-green-50 border-b border-green-100' : ($accion === 'rechazar' ? 'bg-red-50 border-b border-red-100' : 'bg-indigo-50 border-b border-indigo-100') }}">
                    <div>
                        <h3 class="text-base font-semibold
                            {{ $accion === 'aprobar' ? 'text-green-800' : ($accion === 'rechazar' ? 'text-red-800' : 'text-indigo-800') }}">
                            @if ($accion === 'aprobar') Aprobar solicitud
                            @elseif ($accion === 'rechazar') Rechazar solicitud
                            @else Subir acta de entrevista
                            @endif
                        </h3>
                        @if($sol)
                            <p class="text-xs mt-0.5
                                {{ $accion === 'aprobar' ? 'text-green-600' : ($accion === 'rechazar' ? 'text-red-600' : 'text-indigo-600') }}">
                                {{ $sol->grupo->nombre }} — {{ $sol->grupo->caso->nombre }}
                            </p>
                        @endif
                    </div>
                    <button wire:click="cerrarModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="overflow-y-auto flex-1 px-6 py-4 space-y-4">

                    {{-- ── PANEL DE RESUMEN ────────────────────────── --}}
                    @if($sol && $accion !== 'subir_acta')
                        <div class="rounded-lg border border-gray-200 overflow-hidden text-sm">
                            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Resumen de la solicitud</p>
                            </div>
                            <div class="divide-y divide-gray-100">
                                {{-- Alumno solicitante --}}
                                <div class="flex items-start gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-400">Solicitante</p>
                                        <p class="font-medium text-gray-800">{{ $sol->solicitante->nombre_completo }}</p>
                                    </div>
                                </div>
                                {{-- Integrantes del grupo --}}
                                <div class="flex items-start gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-400">Integrantes del grupo</p>
                                        <p class="text-gray-700">{{ $sol->grupo->usuarios->map->nombre_completo->join(', ') }}</p>
                                    </div>
                                </div>
                                {{-- Tipo y fecha --}}
                                <div class="flex items-center gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5l4.586 4.586A2 2 0 0117 9v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2z"/>
                                    </svg>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <span class="px-2 py-0.5 text-xs rounded-full font-medium
                                            {{ $sol->tipo === 'documento' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ ucfirst($sol->tipo) }}
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $sol->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                                {{-- Justificación --}}
                                <div class="flex items-start gap-3 px-4 py-3">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-400 mb-1">Justificación</p>
                                        <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $sol->justificacion }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- ── FIN RESUMEN ─────────────────────────────── --}}

                    {{-- Formulario según acción --}}
                    @if ($accion === 'subir_acta')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Archivo del acta (PDF, DOC o DOCX — máx. 10MB)
                            </label>
                            <input type="file" wire:model="acta"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                       file:rounded file:border-0 file:text-sm file:font-medium
                                       file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @error('acta') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                    @elseif ($accion === 'aprobar' && $sol)
                        @if ($sol->tipo === 'documento')
                            @php
                                $ya_entregados = $docs_entregados_por_grupo->get($sol->grupo_id, collect());
                                $docs_disponibles = $documentos
                                    ->where('caso_id', $sol->grupo->caso_id)
                                    ->reject(fn($doc) => $ya_entregados->contains($doc->id));
                            @endphp
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Documento a entregar
                                </label>
                                @if($docs_disponibles->isEmpty())
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700">
                                        Este grupo ya recibió todos los documentos disponibles del caso.
                                    </div>
                                @else
                                    <select wire:model="recurso_id"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                        <option value="">Seleccionar documento...</option>
                                        @foreach ($docs_disponibles as $doc)
                                            <option value="{{ $doc->id }}">{{ $doc->codigo }} — {{ $doc->titulo }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @if($ya_entregados->count() > 0)
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $ya_entregados->count() }} {{ $ya_entregados->count() === 1 ? 'documento ya entregado' : 'documentos ya entregados' }} a este grupo (no se muestran).
                                    </p>
                                @endif
                                @error('recurso_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @elseif ($sol->tipo === 'entrevista')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Asignar entrevistado
                                </label>
                                <select wire:model="recurso_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                    <option value="">Seleccionar entrevistado...</option>
                                    @foreach ($entrevistados->where('caso_id', $sol->grupo->caso_id) as $entrevistado)
                                        <option value="{{ $entrevistado->id }}">
                                            {{ $entrevistado->nombre }} — {{ $entrevistado->cargo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('recurso_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Acta de entrevista (opcional — podés subirla después)
                                </label>
                                <input type="file" wire:model="acta"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                           file:rounded file:border-0 file:text-sm file:font-medium
                                           file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('acta') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comentario (opcional)</label>
                            <textarea wire:model="comentario" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Comentario opcional..."></textarea>
                            @error('comentario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    @elseif ($accion === 'rechazar')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Motivo del rechazo <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="comentario" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Explicá por qué se rechaza la solicitud..."></textarea>
                            @error('comentario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif

                </div>

                {{-- Footer del modal --}}
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="procesarSolicitud"
                        class="px-4 py-2 text-sm text-white rounded-lg font-medium
                            {{ $accion === 'rechazar' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                        @if ($accion === 'aprobar') Confirmar aprobación
                        @elseif ($accion === 'rechazar') Confirmar rechazo
                        @else Subir acta
                        @endif
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
