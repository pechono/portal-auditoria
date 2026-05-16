<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Filtro por estado --}}
    <div class="flex gap-3 mb-6">
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

    {{-- Tabla --}}
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
                                    <span class="text-xs text-gray-500">
                                        {{ $solicitud->comentario_docente ?? '—' }}
                                    </span>
                                    @if ($solicitud->estado === 'aprobada' && $solicitud->tipo === 'entrevista')
                                        @php $ent = \App\Models\Entrevistado::find($solicitud->recurso_id) @endphp
                                        @if ($ent && $ent->acta_path)
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Acta asignada</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Sin acta</span>
                                        @endif
                                    @elseif ($solicitud->estado === 'aprobada' && $solicitud->tipo === 'documento')
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Documento entregado</span>
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
                            No hay solicitudes {{ $filtro_estado === 'pendiente' ? 'pendientes' : $filtro_estado . 's' }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">

                @php $sol = \App\Models\Solicitud::with('grupo')->find($solicitud_id) @endphp

                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    @if ($accion === 'aprobar') Aprobar solicitud
                    @elseif ($accion === 'rechazar') Rechazar solicitud
                    @else Subir acta de entrevista
                    @endif
                </h3>

                <div class="space-y-4">

                    {{-- Subir acta posterior --}}
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

                    {{-- Aprobar --}}
                    @elseif ($accion === 'aprobar' && $sol)
                        @if ($sol->tipo === 'documento')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Documento a entregar
                                </label>
                                <select wire:model="recurso_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                    <option value="">Seleccionar documento...</option>
                                    @foreach ($documentos->where('caso_id', $sol->grupo->caso_id) as $doc)
                                        <option value="{{ $doc->id }}">{{ $doc->codigo }} — {{ $doc->titulo }}</option>
                                    @endforeach
                                </select>
                                @error('recurso_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @elseif ($sol->tipo === 'entrevista')
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase mb-1">El alumno solicita hablar con:</p>
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $sol->justificacion }}</p>
                            </div>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Comentario (opcional)
                            </label>
                            <textarea wire:model="comentario" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Comentario opcional..."></textarea>
                            @error('comentario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    {{-- Rechazar --}}
                    @elseif ($accion === 'rechazar')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Comentario (obligatorio — explicá por qué)
                            </label>
                            <textarea wire:model="comentario" rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Explicá por qué se rechaza la solicitud..."></textarea>
                            @error('comentario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif

                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="procesarSolicitud"
                        class="px-4 py-2 text-sm text-white rounded-lg
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