<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div class="flex gap-3 mb-6">
        <button wire:click="$set('filtro_estado', 'enviada')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'enviada' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-700 border' }}">
            Pendientes de revisión
        </button>
        <button wire:click="$set('filtro_estado', 'aprobada')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'aprobada' ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border' }}">
            Aprobadas
        </button>
        <button wire:click="$set('filtro_estado', 'con_observaciones')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'con_observaciones' ? 'bg-orange-500 text-white' : 'bg-white text-gray-700 border' }}">
            Con observaciones
        </button>
        <button wire:click="$set('filtro_estado', 'rechazada')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'rechazada' ? 'bg-red-600 text-white' : 'bg-white text-gray-700 border' }}">
            Rechazadas
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo / Caso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Etapa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archivo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha envío</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($entregas as $entrega)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $entrega->grupo->nombre }}</p>
                            <p class="text-xs text-gray-400">{{ $entrega->grupo->caso->nombre }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $entrega->etapa->numero }}. {{ $entrega->etapa->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ asset('uploads/' . $entrega->archivo_path) }}" target="_blank"
                                class="text-sm text-indigo-600 hover:underline">
                                {{ $entrega->archivo_nombre }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $entrega->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="abrirModal({{ $entrega->id }})"
                                class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                Revisar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay entregas en este estado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal revisión --}}
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Revisar entrega</h3>

                <div class="space-y-4">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comentarios</label>
                        <textarea wire:model="comentario" rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Observaciones, correcciones o devolución..."></textarea>
                        @error('comentario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Archivo de devolución (opcional)
                        </label>
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
