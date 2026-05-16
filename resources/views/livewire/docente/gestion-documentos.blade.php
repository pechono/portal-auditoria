<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Filtro por caso --}}
    <div class="flex gap-3 mb-6 items-center">
        <label class="text-sm text-gray-600">Filtrar por caso:</label>
        <select wire:model.live="filtro_caso"
            class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500">
            <option value="">Todos los casos</option>
            @foreach ($casos as $caso)
                <option value="{{ $caso->id }}">{{ $caso->nombre }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acceso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Archivo asignado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($documentos as $documento)
                    <tr>
                        <td class="px-6 py-4 text-xs text-gray-400">
                            {{ $documento->caso->codigo }}
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-600">
                            {{ $documento->codigo }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $documento->titulo }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $documento->descripcion }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if ($documento->acceso_libre)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Libre</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Por solicitud</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($documento->archivo_path)
                                <a href="{{ asset('uploads/' . $documento->archivo_path) }}" target="_blank"
                                    class="text-sm text-indigo-600 hover:underline">
                                    {{ basename($documento->archivo_path) }}
                                </a>
                            @else
                                <span class="text-xs text-red-400">Sin archivo</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="abrirModal({{ $documento->id }})"
                                class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                {{ $documento->archivo_path ? 'Cambiar' : 'Asignar' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay documentos disponibles.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal asignar archivo --}}
    @if ($mostrarModal)
        @php $doc = \App\Models\Documento::find($documento_id) @endphp
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Asignar archivo</h3>
                @if ($doc)
                    <p class="text-sm text-gray-500 mb-4">
                        {{ $doc->codigo }} — {{ $doc->titulo }}
                    </p>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Seleccionar archivo del repositorio
                    </label>
                    @if (count($archivos) > 0)
                        <select wire:model="archivo_elegido"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                            <option value="">Seleccionar archivo...</option>
                            @foreach ($archivos as $archivo)
                                <option value="{{ $archivo }}">{{ $archivo }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-yellow-700">
                                No hay archivos en el repositorio. Subí los archivos a
                                <code class="text-xs bg-yellow-100 px-1 py-0.5 rounded">public/uploads/repositorio/</code>
                                vía FTP.
                            </p>
                        </div>
                    @endif
                    @error('archivo_elegido') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    @if (count($archivos) > 0)
                        <button wire:click="asignarArchivo"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            Asignar
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>