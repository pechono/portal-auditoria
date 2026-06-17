<div>
    @if(session('mensaje'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('mensaje') }}</div>
    @endif

    {{-- Vista detalle / impresión --}}
    @if($detalle)
        <div class="mb-4 flex items-center gap-3 print:hidden">
            <button wire:click="$set('viendo_id', null)" class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800">
                ← Volver al listado
            </button>
            <button onclick="window.print()"
                class="flex items-center gap-1.5 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                🖨 Imprimir enunciado
            </button>
        </div>

        {{-- Enunciado para imprimir --}}
        <div class="bg-white rounded-xl shadow p-8 max-w-3xl mx-auto print:shadow-none print:p-0" id="enunciado">

            {{-- Encabezado --}}
            <div class="border-b-2 border-gray-800 pb-4 mb-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $detalle->nombre }}</h1>
                <p class="text-lg text-gray-600 mt-1">Empresa: <strong>{{ $detalle->empresa }}</strong></p>
                <div class="flex gap-4 mt-2 text-sm text-gray-500">
                    <span>Dificultad:
                        <span class="font-semibold
                            {{ $detalle->dificultad === 'facil' ? 'text-green-600' : ($detalle->dificultad === 'media' ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $detalle->dificultad_label }}
                        </span>
                    </span>
                    <span>Integrantes: <strong>{{ $detalle->integrantes_min }}–{{ $detalle->integrantes_max }}</strong></span>
                </div>
            </div>

            {{-- Antecedentes --}}
            @if($detalle->antecedentes)
                <div class="mb-6">
                    <h2 class="text-base font-bold text-gray-800 uppercase tracking-wide mb-2">Antecedentes</h2>
                    <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $detalle->antecedentes }}</p>
                </div>
            @endif

            {{-- Documentos --}}
            @if($detalle->documentos->count())
                <div class="mb-6">
                    <h2 class="text-base font-bold text-gray-800 uppercase tracking-wide mb-2">Documentación disponible</h2>
                    <ul class="space-y-2">
                        @foreach($detalle->documentos as $doc)
                            <li class="flex items-start gap-2 text-sm text-gray-700">
                                <span class="mt-0.5 text-gray-400">▸</span>
                                <div>
                                    <span class="font-medium">{{ $doc->titulo }}</span>
                                    @if($doc->descripcion)
                                        <span class="text-gray-500"> — {{ $doc->descripcion }}</span>
                                    @endif
                                    @if($doc->archivo_path)
                                        <a href="{{ asset('uploads/' . $doc->archivo_path) }}" target="_blank"
                                            class="ml-2 text-xs text-indigo-600 hover:underline print:hidden">
                                            [Ver archivo]
                                        </a>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Entrevistados --}}
            @if($detalle->entrevistados->count())
                <div class="mb-6">
                    <h2 class="text-base font-bold text-gray-800 uppercase tracking-wide mb-2">Personal disponible para entrevista</h2>
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-gray-300">
                                <th class="text-left py-1.5 font-semibold text-gray-700">Nombre</th>
                                <th class="text-left py-1.5 font-semibold text-gray-700">Cargo</th>
                                <th class="text-left py-1.5 font-semibold text-gray-700">Área</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detalle->entrevistados as $ent)
                                <tr class="border-b border-gray-100">
                                    <td class="py-1.5 text-gray-800">{{ $ent->nombre }}</td>
                                    <td class="py-1.5 text-gray-600">{{ $ent->cargo }}</td>
                                    <td class="py-1.5 text-gray-500">{{ $ent->area }}</td>
                                </tr>
                                @if($ent->descripcion_rol)
                                    <tr class="border-b border-gray-100">
                                        <td colspan="3" class="pb-2 text-xs text-gray-500 italic pl-2">{{ $ent->descripcion_rol }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Pie --}}
            <div class="mt-8 pt-4 border-t border-gray-200 text-xs text-gray-400 text-center print:block hidden">
                Cátedra de Auditoría — Examen Final — {{ now()->format('Y') }}
            </div>
        </div>

    @else

        {{-- LISTADO --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" wire:model.live="soloActivos" class="rounded border-gray-300">
                    Solo activos
                </label>
            </div>
            <button wire:click="nuevoCaso"
                class="flex items-center gap-1.5 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                + Nuevo caso
            </button>
        </div>

        {{-- Modal formulario caso --}}
        @if($mostrarFormulario)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex items-center justify-between px-6 py-4 bg-indigo-600 rounded-t-xl sticky top-0">
                        <h3 class="text-base font-semibold text-white">
                            {{ $editando_id ? 'Editar caso' : 'Nuevo caso final' }}
                        </h3>
                        <button wire:click="$set('mostrarFormulario', false)" class="text-white text-xl">&times;</button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombre del caso *</label>
                                <input type="text" wire:model="nombre" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500">
                                @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Empresa *</label>
                                <input type="text" wire:model="empresa" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500">
                                @error('empresa') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Dificultad</label>
                                <select wire:model="dificultad" class="w-full rounded-lg border-gray-300 text-sm">
                                    <option value="facil">Fácil</option>
                                    <option value="media">Media</option>
                                    <option value="dificil">Difícil</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Integrantes</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" wire:model="integrantes_min" min="1" max="10"
                                        class="w-16 rounded-lg border-gray-300 text-sm text-center">
                                    <span class="text-gray-400 text-sm">a</span>
                                    <input type="number" wire:model="integrantes_max" min="1" max="10"
                                        class="w-16 rounded-lg border-gray-300 text-sm text-center">
                                </div>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Antecedentes / Descripción de la empresa</label>
                                <textarea wire:model="antecedentes" rows="5"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500"
                                    placeholder="Describí la empresa, contexto, problemática..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-5 flex justify-end gap-3 sticky bottom-0 bg-white border-t pt-4">
                        <button wire:click="$set('mostrarFormulario', false)"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                        <button wire:click="guardarCaso"
                            class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Guardar</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Modal agregar documento --}}
        @if($doc_caso_id)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                    <div class="flex items-center justify-between px-6 py-4 bg-blue-600 rounded-t-xl">
                        <h3 class="text-base font-semibold text-white">Agregar documento</h3>
                        <button wire:click="$set('doc_caso_id', null)" class="text-white text-xl">&times;</button>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Título *</label>
                            <input type="text" wire:model="doc_titulo" class="w-full rounded-lg border-gray-300 text-sm">
                            @error('doc_titulo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Descripción breve</label>
                            <input type="text" wire:model="doc_descripcion" class="w-full rounded-lg border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Archivo (PDF, DOC — opcional)</label>
                            <input type="file" wire:model="doc_archivo" accept=".pdf,.doc,.docx"
                                class="block w-full text-sm text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                            @error('doc_archivo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div wire:loading wire:target="doc_archivo" class="text-xs text-gray-400">Subiendo...</div>
                    </div>
                    <div class="px-6 pb-5 flex justify-end gap-3">
                        <button wire:click="$set('doc_caso_id', null)"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg">Cancelar</button>
                        <button wire:click="guardarDocumento"
                            class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Agregar</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Modal agregar entrevistado --}}
        @if($ent_caso_id)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                    <div class="flex items-center justify-between px-6 py-4 bg-emerald-600 rounded-t-xl">
                        <h3 class="text-base font-semibold text-white">Agregar entrevistado</h3>
                        <button wire:click="$set('ent_caso_id', null)" class="text-white text-xl">&times;</button>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Nombre *</label>
                                <input type="text" wire:model="ent_nombre" class="w-full rounded-lg border-gray-300 text-sm">
                                @error('ent_nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Cargo *</label>
                                <input type="text" wire:model="ent_cargo" class="w-full rounded-lg border-gray-300 text-sm">
                                @error('ent_cargo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Área</label>
                                <input type="text" wire:model="ent_area" class="w-full rounded-lg border-gray-300 text-sm">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Descripción del rol</label>
                                <textarea wire:model="ent_descripcion" rows="2"
                                    class="w-full rounded-lg border-gray-300 text-sm"
                                    placeholder="Qué información puede brindar..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-5 flex justify-end gap-3">
                        <button wire:click="$set('ent_caso_id', null)"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg">Cancelar</button>
                        <button wire:click="guardarEntrevistado"
                            class="px-4 py-2 text-sm bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Agregar</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tarjetas de casos --}}
        @forelse($casos as $caso)
            <div class="bg-white rounded-xl shadow mb-4 overflow-hidden {{ !$caso->activo ? 'opacity-60' : '' }}">
                <div class="flex items-center justify-between px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                    <div>
                        <h3 class="text-base font-semibold text-indigo-800">{{ $caso->nombre }}</h3>
                        <p class="text-xs text-indigo-500 mt-0.5">{{ $caso->empresa }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 text-xs rounded-full
                            {{ $caso->dificultad === 'facil' ? 'bg-green-100 text-green-700' : ($caso->dificultad === 'media' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $caso->dificultad_label }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $caso->integrantes_min }}–{{ $caso->integrantes_max }} integrantes</span>
                        <button wire:click="verDetalle({{ $caso->id }})"
                            class="px-3 py-1.5 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Ver / Imprimir
                        </button>
                        <button wire:click="editarCaso({{ $caso->id }})"
                            class="px-3 py-1.5 text-xs bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                            Editar
                        </button>
                        <button wire:click="toggleActivo({{ $caso->id }})"
                            class="px-3 py-1.5 text-xs {{ $caso->activo ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded-lg">
                            {{ $caso->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">

                    {{-- Documentos --}}
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">
                                Documentos <span class="font-normal text-gray-400">({{ $caso->documentos->count() }})</span>
                            </h4>
                            <button wire:click="abrirDocumento({{ $caso->id }})"
                                class="text-xs text-blue-600 hover:underline">+ Agregar</button>
                        </div>
                        @if($caso->documentos->isEmpty())
                            <p class="text-xs text-gray-400">Sin documentos cargados.</p>
                        @else
                            <div class="space-y-1.5">
                                @foreach($caso->documentos as $doc)
                                    <div class="flex items-center justify-between text-xs">
                                        <div>
                                            <span class="font-medium text-gray-700">{{ $doc->titulo }}</span>
                                            @if($doc->descripcion)
                                                <span class="text-gray-400"> — {{ $doc->descripcion }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($doc->archivo_path)
                                                <a href="{{ asset('uploads/' . $doc->archivo_path) }}" target="_blank"
                                                    class="text-indigo-500 hover:underline">↓</a>
                                            @endif
                                            <button wire:click="eliminarDocumento({{ $doc->id }})"
                                                wire:confirm="¿Eliminar este documento?"
                                                class="text-red-400 hover:text-red-600">✕</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Entrevistados --}}
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase">
                                Entrevistados <span class="font-normal text-gray-400">({{ $caso->entrevistados->count() }})</span>
                            </h4>
                            <button wire:click="abrirEntrevistado({{ $caso->id }})"
                                class="text-xs text-emerald-600 hover:underline">+ Agregar</button>
                        </div>
                        @if($caso->entrevistados->isEmpty())
                            <p class="text-xs text-gray-400">Sin entrevistados cargados.</p>
                        @else
                            <div class="space-y-1.5">
                                @foreach($caso->entrevistados as $ent)
                                    <div class="flex items-center justify-between text-xs">
                                        <div>
                                            <span class="font-medium text-gray-700">{{ $ent->nombre }}</span>
                                            <span class="text-gray-400"> — {{ $ent->cargo }}</span>
                                            @if($ent->area)
                                                <span class="text-gray-400"> ({{ $ent->area }})</span>
                                            @endif
                                        </div>
                                        <button wire:click="eliminarEntrevistado({{ $ent->id }})"
                                            wire:confirm="¿Eliminar este entrevistado?"
                                            class="text-red-400 hover:text-red-600">✕</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-10 text-center text-gray-400 text-sm">
                No hay casos finales cargados todavía.
            </div>
        @endforelse

    @endif
</div>
