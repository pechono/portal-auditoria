<div>
    {{-- Tarjetas resumen --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Alumnos registrados</p>
            <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $total_alumnos }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Grupos activos</p>
            <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $total_grupos }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Solicitudes pendientes</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $solicitudes_pendientes }}</p>
            @if ($solicitudes_pendientes > 0)
                <a href="{{ route('docente.solicitudes') }}" class="text-xs text-indigo-600 hover:underline mt-2 block">
                    Ver solicitudes →
                </a>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Entregas por revisar</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $entregas_pendientes }}</p>
            @if ($entregas_pendientes > 0)
                <a href="{{ route('docente.entregas') }}" class="text-xs text-indigo-600 hover:underline mt-2 block">
                    Ver entregas →
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Grupos recientes --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-800">Grupos recientes</h3>
                <a href="{{ route('docente.grupos') }}" class="text-xs text-indigo-600 hover:underline">Ver todos</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($grupos_recientes as $grupo)
                    <div class="px-6 py-3">
                        <p class="text-sm font-medium text-gray-900">{{ $grupo->nombre }}</p>
                        <p class="text-xs text-gray-400">{{ $grupo->caso->nombre }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $grupo->usuarios->map->nombre_completo->join(', ') }}
                        </p>
                    </div>
                @empty
                    <div class="px-6 py-4 text-sm text-gray-400">No hay grupos todavía.</div>
                @endforelse
            </div>
        </div>

        {{-- Solicitudes pendientes --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-800">Solicitudes pendientes</h3>
                <a href="{{ route('docente.solicitudes') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($solicitudes_recientes as $solicitud)
                    <div class="px-6 py-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $solicitud->solicitante->nombre_completo }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $solicitud->grupo->nombre }} — {{ $solicitud->grupo->caso->nombre }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $solicitud->tipo === 'documento' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                {{ ucfirst($solicitud->tipo) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 truncate">{{ $solicitud->justificacion }}</p>
                    </div>
                @empty
                    <div class="px-6 py-4 text-sm text-gray-400">No hay solicitudes pendientes.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
