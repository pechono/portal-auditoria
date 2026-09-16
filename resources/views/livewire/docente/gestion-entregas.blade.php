<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('mensaje') }}</div>
    @endif

    {{-- Controles superiores --}}
    <div class="flex items-center justify-between mb-5" x-data="{ mostrarFinalizados: false }">
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">
                {{ $grupos->where('estado', '!=', 'finalizado')->count() }} grupos activos
            </span>
            <button @click="mostrarFinalizados = !mostrarFinalizados"
                class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 text-gray-500 hover:border-gray-400 hover:text-gray-700 transition"
                x-text="mostrarFinalizados ? 'Ocultar finalizados' : 'Ver finalizados (' + {{ $grupos->where('estado', 'finalizado')->count() }} + ')'">
            </button>
        </div>
        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm">
            <button wire:click="$set('vista', 'grupos')"
                class="px-4 py-2 {{ $vista === 'grupos' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                Tarjetas
            </button>
            <button wire:click="$set('vista', 'lista')"
                class="px-4 py-2 {{ $vista === 'lista' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                Lista
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════ --}}
    {{-- VISTA TARJETAS                           --}}
    {{-- ═══════════════════════════════════════ --}}
    @if($vista === 'grupos')
        <div x-data="{ mostrarFinalizados: false }">

            {{-- Grupos activos --}}
            @php
                $grupos_activos     = $grupos->where('estado', '!=', 'finalizado');
                $grupos_finalizados = $grupos->where('estado', 'finalizado');
            @endphp

            {{-- Toggle finalizados --}}
            <div class="flex items-center gap-3 mb-4">
                <span class="text-sm text-gray-500">
                    {{ $grupos_activos->count() }} grupos activos
                </span>
                @if($grupos_finalizados->count() > 0)
                    <button @click="mostrarFinalizados = !mostrarFinalizados"
                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-300 text-gray-500 hover:border-gray-400 hover:text-gray-700 transition"
                        x-text="mostrarFinalizados ? 'Ocultar finalizados' : 'Ver finalizados ({{ $grupos_finalizados->count() }})'">
                    </button>
                @endif
            </div>

            @forelse($grupos_activos as $grupo)
                @include('livewire.docente.partials.entrega-card', ['grupo' => $grupo, 'autoOpen' => $grupo->_pendientes > 0])
            @empty
                <div class="bg-white rounded-lg shadow p-10 text-center text-gray-400 text-sm">
                    No hay grupos activos con entregas.
                </div>
            @endforelse

            {{-- Grupos finalizados (ocultos por defecto) --}}
            @if($grupos_finalizados->count() > 0)
                <div x-show="mostrarFinalizados" x-transition class="mt-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="h-px flex-1 bg-gray-200"></div>
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Finalizados</span>
                        <div class="h-px flex-1 bg-gray-200"></div>
                    </div>
                    @foreach($grupos_finalizados as $grupo)
                        @include('livewire.docente.partials.entrega-card', ['grupo' => $grupo, 'autoOpen' => false])
                    @endforeach
                </div>
            @endif

        </div>

    @else

    {{-- ═══════════════════════════════════════ --}}
    {{-- VISTA LISTA                             --}}
    {{-- ═══════════════════════════════════════ --}}
    <div class="flex gap-2 mb-4">
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

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo / Caso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Etapa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archivo</th>
                    @if ($filtro_estado !== 'enviada')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Devolución</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
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
                                    @else
                                        <span class="text-xs text-gray-400">Sin comentario</span>
                                    @endif
                                    @if ($entrega->devolucion_path)
                                        <a href="{{ asset('uploads/' . $entrega->devolucion_path) }}" target="_blank"
                                            class="flex items-center gap-1 text-xs text-green-600 hover:underline">↓ Devolución</a>
                                    @endif
                                </div>
                            </td>
                        @endif
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $entrega->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <button wire:click="abrirModal({{ $entrega->id }})"
                                class="px-3 py-1 text-xs rounded {{ $filtro_estado === 'enviada' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border' }}">
                                {{ $filtro_estado === 'enviada' ? 'Revisar' : 'Editar' }}
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
                                <a href="{{ asset('uploads/' . $entrega_modal->devolucion_path) }}" target="_blank"
                                    class="text-green-600 hover:underline">Ver devolución actual</a>
                                <span class="text-gray-400">(subir uno nuevo lo reemplaza)</span>
                            </div>
                        @endif
                        <input type="file" wire:model="devolucion" accept=".pdf,.doc,.docx"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                   file:rounded file:border-0 file:text-sm file:font-medium
                                   file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('devolucion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                    <button wire:click="procesarEntrega"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Guardar revisión</button>
                </div>
            </div>
        </div>
    @endif
</div>
