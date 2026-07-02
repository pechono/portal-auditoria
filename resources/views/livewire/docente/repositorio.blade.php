<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <input type="text" wire:model.live="buscar"
                class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500"
                placeholder="Buscar archivo...">
            <select wire:model.live="filtro_categoria"
                class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500">
                <option value="">Todas las categorías</option>
                <option value="documento">Documentos</option>
                <option value="entrevista">Entrevistas</option>
                <option value="otro">Otros</option>
            </select>
            <select wire:model.live="filtro_caso"
                class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500">
                <option value="">Todos los casos</option>
                @foreach ($casos as $caso)
                    <option value="{{ $caso->id }}">{{ $caso->codigo }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="abrirModal"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
            + Subir archivo
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($archivos as $archivo)
                    <tr>
                        <td class="px-6 py-4">
                            <a href="{{ asset('uploads/' . $archivo->path) }}" target="_blank"
                                class="text-sm text-indigo-600 hover:underline">
                                {{ $archivo->nombre }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $archivo->nombre_original }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $archivo->categoria === 'documento' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $archivo->categoria === 'entrevista' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $archivo->categoria === 'otro' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ ucfirst($archivo->categoria) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $archivo->caso?->codigo ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $archivo->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <button
                                wire:click="eliminarArchivo({{ $archivo->id }})"
                                wire:confirm="¿Eliminar este archivo?"
                                class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay archivos en el repositorio todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal subir archivo --}}
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Subir archivo al repositorio</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del archivo</label>
                        <input type="text" wire:model="nombre_personalizado"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: acta-fabio-herrera, fmc-organigrama...">
                        @error('nombre_personalizado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                            <select wire:model="categoria"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                <option value="documento">Documento</option>
                                <option value="entrevista">Entrevista</option>
                                <option value="otro">Otro</option>
                            </select>
                            @error('categoria') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Caso</label>
                            <select wire:model="caso_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                <option value="">Sin caso específico</option>
                                @foreach ($casos as $caso)
                                    <option value="{{ $caso->id }}">{{ $caso->codigo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Archivo (PDF, DOC o DOCX — máx. 20MB)
                        </label>
                        <input type="file" wire:model="archivo" accept=".pdf,.doc,.docx"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                   file:rounded file:border-0 file:text-sm file:font-medium
                                   file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <div wire:loading wire:target="archivo" class="text-xs text-indigo-600 mt-1">
                            Cargando archivo...
                        </div>
                        @error('archivo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="subirArchivo"
                        wire:loading.attr="disabled"
                        wire:target="subirArchivo"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="subirArchivo">Subir</span>
                        <span wire:loading wire:target="subirArchivo">Subiendo...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>