<div>
    @if (session()->has('mensaje_entrega'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje_entrega') }}
        </div>
    @endif

    @if (!$grupo)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-yellow-700">Todavía no estás asignado a ningún grupo.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Etapa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Historial de entregas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($etapas as $etapa)
                        @php
                            $entregas_etapa = $entregas->get($etapa->id, collect());
                            $ultima_entrega = $entregas_etapa->last();
                            $sin_entrega    = $etapa->numero === 1;
                            $bloqueada      = ($etapa->numero === 3 && !$plan_aprobado)
                                           || (in_array($etapa->numero, [4, 5]) && !$etapa_3_aprobada);
                        @endphp
                        <tr class="{{ $bloqueada ? 'opacity-50' : '' }}">
                            {{-- Nombre etapa --}}
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $etapa->numero }}. {{ $etapa->nombre }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $etapa->descripcion }}</p>
                            </td>

                            {{-- Estado --}}
                            <td class="px-6 py-4">
                                @if ($sin_entrega)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Informativa</span>
                                @elseif ($bloqueada)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-400 rounded-full">🔒 Bloqueada</span>
                                @elseif (!$ultima_entrega)
                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Sin entregar</span>
                                @elseif ($ultima_entrega->estado === 'enviada')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Esperando revisión</span>
                                @elseif ($ultima_entrega->estado === 'aprobada')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">✓ Aprobada</span>
                                @elseif ($ultima_entrega->estado === 'con_observaciones')
                                    <span class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded-full">Con observaciones</span>
                                @elseif ($ultima_entrega->estado === 'rechazada')
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Rechazada</span>
                                @endif
                            </td>

                            {{-- Historial de entregas --}}
                            <td class="px-6 py-4">
                                @if ($sin_entrega || $bloqueada || $entregas_etapa->isEmpty())
                                    <span class="text-xs text-gray-400">—</span>
                                @else
                                    <div class="space-y-3">
                                        @foreach ($entregas_etapa as $i => $entrega)
                                            <div class="border border-gray-100 rounded-lg p-2 bg-gray-50">
                                                <p class="text-xs font-medium text-gray-500 mb-1">
                                                    Intento {{ $i + 1 }} — {{ $entrega->created_at->format('d/m/Y H:i') }}
                                                </p>
                                                <div class="flex flex-col gap-1">
                                                    {{-- Mi entrega --}}
                                                    <a href="{{ asset('uploads/' . $entrega->archivo_path) }}" target="_blank"
                                                        class="flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                                                        📄 {{ $entrega->archivo_nombre }}
                                                    </a>
                                                    {{-- Devolución docente --}}
                                                    @if ($entrega->devolucion_path)
                                                        <a href="{{ asset('uploads/' . $entrega->devolucion_path) }}" target="_blank"
                                                            class="flex items-center gap-1 text-xs text-green-600 hover:underline">
                                                            📝 Devolución docente
                                                        </a>
                                                    @endif
                                                    {{-- Comentario --}}
                                                    @if ($entrega->comentario_docente)
                                                        <p class="text-xs text-gray-500 italic mt-1">
                                                            💬 {{ $entrega->comentario_docente }}
                                                        </p>
                                                    @endif
                                                    {{-- Estado del intento --}}
                                                    <span class="text-xs
                                                        @if($entrega->estado === 'aprobada') text-green-600
                                                        @elseif($entrega->estado === 'rechazada') text-red-500
                                                        @elseif($entrega->estado === 'con_observaciones') text-orange-500
                                                        @else text-yellow-600
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $entrega->estado)) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            {{-- Acción --}}
                            <td class="px-6 py-4">
                                @if ($sin_entrega)
                                    <span class="text-xs text-blue-500">Sin entrega requerida</span>
                                @elseif ($bloqueada)
                                    <span class="text-xs text-gray-400">🔒 Bloqueada</span>
                                @elseif ($etapa->numero === 5)
                                    <span class="text-xs text-gray-400">Presencial</span>
                                @elseif (!$ultima_entrega || in_array($ultima_entrega->estado, ['rechazada', 'con_observaciones']))
                                    <button wire:click="abrirModal({{ $etapa->id }})"
                                        class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                        Subir entrega
                                    </button>
                                @elseif ($ultima_entrega->estado === 'enviada')
                                    <span class="text-xs text-yellow-600">Esperando revisión</span>
                                @elseif ($ultima_entrega->estado === 'aprobada')
                                    <span class="text-xs text-green-600">✓ Completada</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Modal subir entrega --}}
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Subir entrega</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Archivo (PDF, DOC o DOCX — máx. 10MB)
                    </label>
                    <input type="file" wire:model="archivo"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                               file:rounded file:border-0 file:text-sm file:font-medium
                               file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('archivo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="subirEntrega"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Enviar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>