<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Toggle vista --}}
    <div class="flex items-center gap-3 mb-5">
        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm">
            <button wire:click="$set('vista', 'grupos')"
                class="px-4 py-2 {{ $vista === 'grupos' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                Por grupo
            </button>
            <button wire:click="$set('vista', 'lista')"
                class="px-4 py-2 {{ $vista === 'lista' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                Lista
            </button>
        </div>

        {{-- Filtro estado (solo en vista lista) --}}
        @if($vista === 'lista')
            <div class="flex gap-2">
                <button wire:click="$set('filtro_estado', 'enviada')"
                    class="px-3 py-2 text-sm rounded-lg {{ $filtro_estado === 'enviada' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 border' }}">
                    Pendientes
                </button>
                <button wire:click="$set('filtro_estado', 'aprobada')"
                    class="px-3 py-2 text-sm rounded-lg {{ $filtro_estado === 'aprobada' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border' }}">
                    Aprobadas
                </button>
                <button wire:click="$set('filtro_estado', 'con_observaciones')"
                    class="px-3 py-2 text-sm rounded-lg {{ $filtro_estado === 'con_observaciones' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border' }}">
                    Con observaciones
                </button>
                <button wire:click="$set('filtro_estado', 'rechazada')"
                    class="px-3 py-2 text-sm rounded-lg {{ $filtro_estado === 'rechazada' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 border' }}">
                    Rechazadas
                </button>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- VISTA POR GRUPO                         --}}
    {{-- ═══════════════════════════════════════ --}}
    @if($vista === 'grupos')
        @forelse($grupos as $grupo)
            @php
                $pct   = $grupo->_pct;
                $color = $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-yellow-400' : 'bg-red-400');
                $textColor = $pct >= 80 ? 'text-green-700' : ($pct >= 50 ? 'text-yellow-700' : 'text-red-600');
            @endphp
            <div class="bg-white rounded-xl shadow mb-5 overflow-hidden">

                {{-- Cabecera del grupo --}}
                <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h3 class="text-base font-semibold text-indigo-800">{{ $grupo->nombre }}</h3>
                            <p class="text-xs text-indigo-400 mt-0.5">{{ $grupo->caso->nombre }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            @if($grupo->_pendientes > 0)
                                <span class="flex items-center gap-1 px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                    {{ $grupo->_pendientes }} pendiente{{ $grupo->_pendientes > 1 ? 's' : '' }}
                                </span>
                            @endif
                            @if($grupo->_con_obs > 0)
                                <span class="flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                                    {{ $grupo->_con_obs }} con obs.
                                </span>
                            @endif
                            @if($grupo->_rechazadas > 0)
                                <span class="flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    {{ $grupo->_rechazadas }} rechazada{{ $grupo->_rechazadas > 1 ? 's' : '' }}
                                </span>
                            @endif
                            <span class="font-semibold {{ $textColor }} text-sm">{{ $pct }}%</span>
                        </div>
                    </div>

                    {{-- Barra de progreso --}}
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="{{ $color }} h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>Etapa {{ $grupo->_max_etapa }} de {{ $grupo->_total_etapas }}</span>
                        <span>{{ $grupo->_aprobadas }} aprobada{{ $grupo->_aprobadas !== 1 ? 's' : '' }}</span>
                    </div>
                </div>

                {{-- Entregas del grupo --}}
                <div class="divide-y divide-gray-100">
                    @foreach($grupo->entregas->sortBy('etapa.numero') as $entrega)
                        <div class="flex items-center justify-between px-6 py-3">
                            <div class="flex items-center gap-3">
                                {{-- Estado indicador --}}
                                @php
                                    $dot = match($entrega->estado) {
                                        'aprobada'         => 'bg-green-500',
                                        'enviada'          => 'bg-yellow-400',
                                        'con_observaciones'=> 'bg-orange-400',
                                        'rechazada'        => 'bg-red-500',
                                        default            => 'bg-gray-300',
                                    };
                                @endphp
                                <span class="w-2 h-2 rounded-full {{ $dot }} flex-shrink-0"></span>
                                <div>
                                    <p class="text-sm text-gray-800">
                                        {{ $entrega->etapa->numero }}. {{ $entrega->etapa->nombre }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $entrega->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @php
                                    $badge = match($entrega->estado) {
                                        'aprobada'         => 'bg-green-100 text-green-700',
                                        'enviada'          => 'bg-yellow-100 text-yellow-700',
                                        'con_observaciones'=> 'bg-orange-100 text-orange-700',
                                        'rechazada'        => 'bg-red-100 text-red-700',
                                        default            => 'bg-gray-100 text-gray-500',
                                    };
                                    $label = match($entrega->estado) {
                                        'aprobada'         => 'Aprobada',
                                        'enviada'          => 'Pendiente',
                                        'con_observaciones'=> 'Con obs.',
                                        'rechazada'        => 'Rechazada',
                                        default            => $entrega->estado,
                                    };
                                @endphp
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $badge }}">{{ $label }}</span>
                                @if($entrega->nota !== null)
                                    <span class="text-xs font-semibold text-indigo-600">{{ number_format($entrega->nota, 1) }}</span>
                                @endif
                                <a href="{{ asset('uploads/' . $entrega->archivo_path) }}" target="_blank"
                                    class="text-xs text-gray-400 hover:text-indigo-600" title="Ver archivo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                    </svg>
                                </a>
                                <button wire:click="abrirModal({{ $entrega->id }})"
                                    class="px-3 py-1 text-xs rounded {{ $entrega->estado === 'enviada' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border' }}">
                                    {{ $entrega->estado === 'enviada' ? 'Revisar' : 'Editar' }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-10 text-center text-gray-400 text-sm">
                No hay entregas registradas.
            </div>
        @endforelse

    @else

    {{-- ═══════════════════════════════════════ --}}
    {{-- VISTA LISTA                             --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo / Caso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Etapa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrega del alumno</th>
                    @if ($filtro_estado !== 'enviada')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Devolución</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha envío</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($entregas as $entrega)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $entrega->grupo->nombre }}</p>
                            <p class="text-xs text-gray-400">{{ $entrega->grupo->caso->nombre }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $entrega->etapa->numero }}. {{ $entrega->etapa->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ asset('uploads/' . $entrega->archivo_path) }}" target="_blank"
                                class="flex items-center gap-1 text-sm text-indigo-600 hover:underline">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                                {{ $entrega->archivo_nombre }}
                            </a>
                        </td>
                        @if ($filtro_estado !== 'enviada')
                            <td class="px-6 py-4 max-w-xs">
                                <div class="space-y-1.5">
                                    @if ($entrega->nota !== null)
                                        <p class="text-sm font-semibold text-gray-800">
                                            Nota: <span class="text-indigo-600">{{ number_format($entrega->nota, 2) }} / 10</span>
                                        </p>
                                    @endif
                                    @if ($entrega->comentario_docente)
                                        <p class="text-xs text-gray-600 italic line-clamp-2">"{{ $entrega->comentario_docente }}"</p>
                                        <button onclick="document.getElementById('com-{{ $entrega->id }}').classList.toggle('hidden')"
                                            class="text-xs text-indigo-500 hover:underline">Ver completo</button>
                                        <p id="com-{{ $entrega->id }}" class="hidden text-xs text-gray-600 mt-1 whitespace-pre-wrap border-l-2 border-indigo-200 pl-2">
                                            {{ $entrega->comentario_docente }}
                                        </p>
                                    @else
                                        <span class="text-xs text-gray-400">Sin comentario</span>
                                    @endif
                                    @if ($entrega->devolucion_path)
                                        <a href="{{ asset('uploads/' . $entrega->devolucion_path) }}" target="_blank"
                                            class="flex items-center gap-1 text-xs text-green-600 hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Archivo de devolución
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">Sin archivo adjunto</span>
                                    @endif
                                    @if ($entrega->revisado_at)
                                        <p class="text-xs text-gray-400">Revisado: {{ $entrega->revisado_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </td>
                        @endif
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $entrega->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="abrirModal({{ $entrega->id }})"
                                class="px-3 py-1 text-xs rounded {{ $filtro_estado === 'enviada' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border' }}">
                                {{ $filtro_estado === 'enviada' ? 'Revisar' : 'Editar devolución' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $filtro_estado !== 'enviada' ? 6 : 5 }}"
                            class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay entregas en este estado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @endif

    {{-- Modal revisión --}}
    @if ($mostrarModal)
        @php $entrega_modal = \App\Models\Entrega::with(['grupo', 'etapa'])->find($entrega_id) @endphp
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">

                <h3 class="text-lg font-semibold text-gray-800 mb-1">
                    {{ $entrega_modal?->estado === 'enviada' ? 'Revisar entrega' : 'Editar devolución' }}
                </h3>
                @if ($entrega_modal)
                    <p class="text-xs text-gray-400 mb-4">
                        {{ $entrega_modal->grupo->nombre }} — {{ $entrega_modal->etapa->numero }}. {{ $entrega_modal->etapa->nombre }}
                    </p>
                @endif

                <div class="space-y-4">
                    @if ($entrega_modal)
                        <div class="p-3 bg-gray-50 rounded-lg flex items-center justify-between">
                            <span class="text-xs text-gray-500">Archivo del alumno:</span>
                            <a href="{{ asset('uploads/' . $entrega_modal->archivo_path) }}" target="_blank"
                                class="text-xs text-indigo-600 hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                {{ $entrega_modal->archivo_nombre }}
                            </a>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                        <select wire:model="estado"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                            <option value="">Seleccionar...</option>
                            <option value="aprobada">Aprobada</option>
                            <option value="con_observaciones">Con observaciones</option>
                            <option value="rechazada">Rechazada</option>
                        </select>
                        @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nota (opcional, sobre 10)</label>
                        <input type="number" wire:model="nota" step="0.01" min="0" max="10"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: 8.50">
                        @error('nota') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comentarios / Devolución</label>
                        <textarea wire:model="comentario" rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Observaciones, correcciones o devolución..."></textarea>
                        @error('comentario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Archivo de devolución (opcional)</label>
                        @if ($entrega_modal && $entrega_modal->devolucion_path)
                            <div class="mb-2 flex items-center gap-2 text-xs text-gray-500">
                                <span>Archivo actual:</span>
                                <a href="{{ asset('uploads/' . $entrega_modal->devolucion_path) }}" target="_blank"
                                    class="text-green-600 hover:underline">Ver devolución actual</a>
                                <span class="text-gray-400">(subir uno nuevo lo reemplaza)</span>
                            </div>
                        @endif
                        <input type="file" wire:model="devolucion"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                   file:rounded file:border-0 file:text-sm file:font-medium
                                   file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('devolucion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="procesarEntrega"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Guardar revisión
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
