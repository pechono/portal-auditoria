<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    @if (!$grupo)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-yellow-700">Todavía no estás asignado a ningún grupo. Esperá que el docente te asigne uno.</p>
        </div>
    @else
        {{-- Info del caso --}}
        <div class="mb-6 p-4 bg-indigo-50 rounded-lg">
            <p class="text-indigo-700 font-medium text-lg">{{ $grupo->caso->nombre }}</p>
            <p class="text-indigo-500 text-sm mt-1">{{ $grupo->caso->descripcion }}</p>
        </div>

        {{-- Documentos de acceso libre --}}
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Documentos iniciales</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($docs_libres as $doc)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $doc->titulo }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $doc->descripcion }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($doc->archivo_path)
                                        <a href="{{ asset('uploads/' . $doc->archivo_path) }}" target="_blank"
                                            class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                            Descargar
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">Sin archivo aún</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No hay documentos iniciales disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Solicitar documentos --}}
        <div class="mb-8">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Solicitar documentos</h3>
                <button wire:click="abrirModalDocumento"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                    + Nueva solicitud
                </button>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitud</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comentario docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($solicitudes_docs as $sol)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ Str::limit($sol->justificacion, 80) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sol->estado === 'pendiente')
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Pendiente</span>
                                    @elseif ($sol->estado === 'aprobada')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Aprobada</span>
                                    @elseif ($sol->estado === 'rechazada')
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Rechazada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $sol->comentario_docente ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sol->estado === 'aprobada' && $sol->recurso_id > 0)
                                        @php $doc = \App\Models\Documento::find($sol->recurso_id) @endphp
                                        @if ($doc && $doc->archivo_path)
                                            <a href="{{ asset('uploads/' . $doc->archivo_path) }}" target="_blank"
                                                class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                                Descargar
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No hay solicitudes de documentos todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Solicitar entrevistas --}}
        <div class="mb-8">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Solicitar entrevistas</h3>
                <button wire:click="abrirModalEntrevista"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                    + Nueva solicitud
                </button>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitud</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comentario docente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($solicitudes_ents as $sol_ent)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ Str::limit($sol_ent->justificacion, 80) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sol_ent->estado === 'pendiente')
                                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Pendiente</span>
                                    @elseif ($sol_ent->estado === 'aprobada')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Aprobada</span>
                                    @elseif ($sol_ent->estado === 'rechazada')
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Rechazada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $sol_ent->comentario_docente ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{-- @if ($sol_ent->estado === 'aprobada')
                                        @if ($sol_ent->acta_path)
                                            <a href="{{ asset('uploads/' . $sol_ent->acta_path) }}" target="_blank"
                                                class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                                Descargar acta
                                            </a>
                                        @else
                                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">
                                                Acta pendiente
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif --}}

                                    @if ($sol_ent->estado === 'aprobada')
                                    @php
                                        $entrevistado_acta = $sol_ent->recurso_id
                                            ? \App\Models\Entrevistado::find($sol_ent->recurso_id)
                                            : null;
                                        $acta = $sol_ent->acta_path ?? $entrevistado_acta?->acta_path;
                                    @endphp
                                    @if ($acta)
                                        <a href="{{ asset('uploads/' . $acta) }}" target="_blank"
                                            class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                             Descargar
                                        </a>
                                    @else
                                        <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">
                                             Pendiente
                                            
                                        </span>
                                    @endif
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No hay solicitudes de entrevistas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Modal solicitar documento --}}
    @if ($mostrarModalDocumento)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Solicitar documento</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Indicá qué documento necesitás y justificá técnicamente por qué lo necesitás para la auditoría.
                </p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">¿Qué tipo de recurso necesitás?</label>
                        <select wire:model="documento_solicitado"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                            <option value="">Seleccionar tipo...</option>
                            <option value="Registros contables">Registros contables</option>
                            <option value="Registros del sistema / logs">Registros del sistema / logs</option>
                            <option value="Contratos y documentación legal">Contratos y documentación legal</option>
                            <option value="Políticas y normativas internas">Políticas y normativas internas</option>
                            <option value="Auditorías o informes previos">Auditorías o informes previos</option>
                            <option value="Manual del sistema">Manual del sistema</option>
                            <option value="Otro">Otro (especificá en la justificación)</option>
                        </select>
                        @error('documento_solicitado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Justificación técnica</label>
                        <textarea wire:model="justificacion_doc" rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Explicá por qué necesitás este documento y cómo lo vas a usar en la auditoría..."></textarea>
                        @error('justificacion_doc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModalDocumento"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="enviarSolicitudDocumento"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Enviar solicitud
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal solicitar entrevista --}}
    @if ($mostrarModalEntrevista)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Solicitar entrevista</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Indicá con quién querés hablar y justificá por qué es relevante para la auditoría.
                </p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            ¿Con quién querés hablar?
                        </label>
                        <input type="text" wire:model="persona_solicitada"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: responsable del sistema, gerente de logística, contador...">
                        @error('persona_solicitada') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Justificación técnica</label>
                        <textarea wire:model="justificacion_ent" rows="4"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Explicá qué información buscás obtener y por qué es relevante para la auditoría..."></textarea>
                        @error('justificacion_ent') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModalEntrevista"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="enviarSolicitudEntrevista"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Enviar solicitud
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>