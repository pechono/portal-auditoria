<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Lista de casos --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800">Casos de estudio</h3>
                    <button wire:click="abrirModalCaso()"
                        class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        + Nuevo
                    </button>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($casos as $caso)
                        <div class="px-6 py-4 {{ $caso_seleccionado?->id === $caso->id ? 'bg-indigo-50' : '' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $caso->nombre }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $caso->codigo }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ $caso->activo ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $caso->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <button wire:click="seleccionarCaso({{ $caso->id }}, 'documentos')"
                                    class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                    Documentos
                                </button>
                                <button wire:click="seleccionarCaso({{ $caso->id }}, 'entrevistados')"
                                    class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200">
                                    Entrevistados
                                </button>
                                <button wire:click="abrirModalCaso({{ $caso->id }})"
                                    class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                                    Editar
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-400">No hay casos creados todavía.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel derecho --}}
        <div class="lg:col-span-2">
            @if (!$caso_seleccionado)
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <p class="text-gray-400">Seleccioná un caso para gestionar sus documentos o entrevistados.</p>
                </div>
            @else
                {{-- Documentos --}}
                @if ($panel === 'documentos')
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">Documentos</h3>
                                <p class="text-xs text-gray-400">{{ $caso_seleccionado->nombre }}</p>
                            </div>
                            <button wire:click="abrirModalDoc()"
                                class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                + Agregar
                            </button>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($documentos as $doc)
                                <div class="px-6 py-4">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-mono text-gray-500">{{ $doc->codigo }}</span>
                                                <span class="px-2 py-0.5 text-xs rounded-full {{ $doc->acceso_libre ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ $doc->acceso_libre ? 'Libre' : 'Por solicitud' }}
                                                </span>
                                            </div>
                                            <p class="text-sm font-medium text-gray-900 mt-1">{{ $doc->titulo }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $doc->descripcion }}</p>
                                            @if ($doc->archivo_path)
                                                <a href="{{ asset('uploads/' . $doc->archivo_path) }}" target="_blank"
                                                    class="text-xs text-indigo-600 hover:underline mt-1 block">
                                                    📄 {{ basename($doc->archivo_path) }}
                                                </a>
                                            @else
                                                <span class="text-xs text-red-400 mt-1 block">Sin archivo asignado</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <button wire:click="abrirModalDoc({{ $doc->id }})"
                                                class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                                                Editar
                                            </button>
                                            <button wire:click="eliminarDocumento({{ $doc->id }})"
                                                wire:confirm="¿Eliminar este documento?"
                                                class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">
                                                Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-4 text-sm text-gray-400">No hay documentos en este caso.</div>
                            @endforelse
                        </div>
                    </div>
                @endif

                {{-- Entrevistados --}}
                @if ($panel === 'entrevistados')
                    <div class="bg-white rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">Entrevistados</h3>
                                <p class="text-xs text-gray-400">{{ $caso_seleccionado->nombre }}</p>
                            </div>
                            <button wire:click="abrirModalEnt()"
                                class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                + Agregar
                            </button>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse ($entrevistados as $ent)
                                <div class="px-6 py-4 flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $ent->nombre }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $ent->cargo }} — {{ $ent->area }}</p>
                                        @if ($ent->descripcion_rol)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $ent->descripcion_rol }}</p>
                                        @endif
                                        @if ($ent->acta_path)
                                            <span class="text-xs text-green-600 mt-0.5 block">✓ Acta asignada</span>
                                        @else
                                            <span class="text-xs text-red-400 mt-0.5 block">Sin acta asignada</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button wire:click="abrirModalEnt({{ $ent->id }})"
                                            class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                                            Editar
                                        </button>
                                        <button wire:click="eliminarEntrevistado({{ $ent->id }})"
                                            wire:confirm="¿Eliminar este entrevistado?"
                                            class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded hover:bg-red-200">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-4 text-sm text-gray-400">No hay entrevistados en este caso.</div>
                            @endforelse
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- Modal caso --}}
    @if ($mostrarModalCaso)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_caso ? 'Editar caso' : 'Nuevo caso de estudio' }}
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                            <input type="text" wire:model="codigo"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Ej: FMC, CSIV">
                            @error('codigo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center gap-2 mt-6">
                            <input type="checkbox" wire:model="activo" id="activo" class="rounded border-gray-300">
                            <label for="activo" class="text-sm text-gray-700">Caso activo</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la empresa</label>
                        <input type="text" wire:model="nombre"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: Ferretería Mayorista del Centro S.A.">
                        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción breve
                            <span class="text-xs text-gray-400 font-normal">(visible para el alumno — no revelar el problema)</span>
                        </label>
                        <textarea wire:model="descripcion" rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: Empresa mayorista ferretera. Sistema de gestión de inventario FMCGEST."></textarea>
                        @error('descripcion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModalCaso"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarCaso"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_caso ? 'Guardar cambios' : 'Crear caso' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal documento --}}
    @if ($mostrarModalDoc)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_doc ? 'Editar documento' : 'Agregar documento' }}
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Archivo del repositorio
                        </label>
                        @if ($archivos_docs->count() > 0)
                            <select wire:model="doc_archivo" wire:change="archivoSeleccionadoDoc"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                <option value="">Sin archivo asignado</option>
                                @foreach ($archivos_docs as $arch)
                                    <option value="{{ $arch->id }}">{{ $arch->nombre }}</option>
                                @endforeach
                            </select>
                        @else
                            <p class="text-xs text-yellow-600 p-2 bg-yellow-50 rounded">
                                No hay archivos de categoría "documento" para este caso en el repositorio.
                                Subí archivos desde la sección Repositorio seleccionando categoría "Documento" y este caso.
                            </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                            <input type="text" wire:model="doc_codigo"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Ej: DOC-01">
                            @error('doc_codigo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center gap-2 mt-6">
                            <input type="checkbox" wire:model="doc_acceso_libre" id="acceso_libre" class="rounded border-gray-300">
                            <label for="acceso_libre" class="text-sm text-gray-700">Acceso libre</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                        <input type="text" wire:model="doc_titulo"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Se completa automáticamente al elegir el archivo">
                        @error('doc_titulo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea wire:model="doc_descripcion" rows="2"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Descripción breve del documento..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModalDoc"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarDocumento"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_doc ? 'Guardar cambios' : 'Agregar' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal entrevistado --}}
    @if ($mostrarModalEnt)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_ent ? 'Editar entrevistado' : 'Agregar entrevistado' }}
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                        <input type="text" wire:model="ent_nombre"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: Diego Ramos">
                        @error('ent_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cargo</label>
                            <input type="text" wire:model="ent_cargo"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Ej: Jefe de Sistemas">
                            @error('ent_cargo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                            <input type="text" wire:model="ent_area"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Ej: Sistemas">
                            @error('ent_area') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción del rol</label>
                        <textarea wire:model="ent_descripcion" rows="2"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Descripción de las responsabilidades..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Acta de entrevista
                        </label>
                        @if ($archivos_ents->count() > 0)
                            <select wire:model="ent_acta"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                                <option value="">Sin acta asignada</option>
                                @foreach ($archivos_ents as $arch)
                                    <option value="{{ $arch->id }}">{{ $arch->nombre }}</option>
                                @endforeach
                            </select>
                        @else
                            <p class="text-xs text-yellow-600 p-2 bg-yellow-50 rounded">
                                No hay archivos de categoría "entrevista" para este caso en el repositorio.
                                Subí archivos desde la sección Repositorio seleccionando categoría "Entrevista" y este caso.
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModalEnt"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarEntrevistado"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_ent ? 'Guardar cambios' : 'Agregar' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>