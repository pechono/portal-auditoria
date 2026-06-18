<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Modal subida devolución final --}}
    @if($subiendo_grupo_id)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                <div class="flex items-center justify-between px-6 py-4 bg-blue-600 rounded-t-xl">
                    <h3 class="text-base font-semibold text-white">Subir devolución final</h3>
                    <button wire:click="$set('subiendo_grupo_id', null)" class="text-white hover:text-blue-200 text-xl leading-none">&times;</button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <p class="text-sm text-gray-500">Seleccioná el archivo PDF o Word con la devolución final para que los alumnos puedan descargarlo el día de la presentación.</p>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Archivo (PDF, DOC, DOCX — máx. 20 MB)</label>
                        <input type="file" wire:model="devolucion_final" accept=".pdf,.doc,.docx"
                            class="block w-full text-sm text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('devolucion_final') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div wire:loading wire:target="devolucion_final" class="flex items-center gap-2 text-xs text-gray-400">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Subiendo archivo...
                    </div>
                </div>
                <div class="px-6 pb-5 flex justify-end gap-3">
                    <button wire:click="$set('subiendo_grupo_id', null)"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="subirDevolucionFinal" wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        Subir devolución
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Filtro de estado --}}
    <div class="flex gap-2 mb-6">
        <button wire:click="$set('filtro_estado', 'activo')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'activo' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border' }}">
            Activos
        </button>
        <button wire:click="$set('filtro_estado', 'finalizado')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'finalizado' ? 'bg-gray-700 text-white' : 'bg-white text-gray-700 border' }}">
            Finalizados
        </button>
        <button wire:click="$set('filtro_estado', 'todos')"
            class="px-4 py-2 text-sm rounded-lg {{ $filtro_estado === 'todos' ? 'bg-gray-500 text-white' : 'bg-white text-gray-700 border' }}">
            Todos
        </button>
    </div>

    @forelse ($grupos as $grupo)
        @php
            $caso_id      = $grupo->caso_id;
            $docs_caso    = $documentos_por_caso->get($caso_id, collect());
            $ents_caso    = $entrevistados_por_caso->get($caso_id, collect());

            $docs_entregados  = $grupo->solicitudes->where('tipo', 'documento')->pluck('recurso_id')->unique();
            $ents_realizadas  = $grupo->solicitudes->where('tipo', 'entrevista')->pluck('recurso_id')->unique();

            $notas    = $grupo->entregas->pluck('nota')->filter();
            $promedio = $notas->count() > 0 ? round($notas->avg(), 2) : null;

            $finalizado = $grupo->estado === 'finalizado';
        @endphp

        <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">

            {{-- Cabecera --}}
            <div class="flex items-center justify-between px-6 py-4
                {{ $finalizado ? 'bg-gray-100 border-b border-gray-200' : 'bg-indigo-50 border-b border-indigo-100' }}">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold {{ $finalizado ? 'text-gray-500' : 'text-indigo-800' }}">
                            {{ $grupo->nombre }}
                        </h3>
                        @if($finalizado)
                            <span class="px-2 py-0.5 text-xs bg-gray-200 text-gray-600 rounded-full">Finalizado</span>
                        @endif
                    </div>
                    <p class="text-xs {{ $finalizado ? 'text-gray-400' : 'text-indigo-500' }} mt-0.5">
                        {{ $grupo->caso->nombre }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($promedio !== null)
                        <div class="text-center">
                            <p class="text-xs text-gray-400">Promedio entregas</p>
                            <p class="text-lg font-bold {{ $promedio >= 9 ? 'text-green-600' : ($promedio >= 6 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $promedio }}
                            </p>
                        </div>
                    @endif

                    {{-- Devolución final --}}
                    @if($grupo->devolucion_final_path)
                        <div class="flex flex-col items-end gap-1">
                            <a href="{{ asset('uploads/' . $grupo->devolucion_final_path) }}" target="_blank"
                                class="flex items-center gap-1.5 px-3 py-2 text-xs bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                                Devolución final
                            </a>
                            <button wire:click="abrirSubidaDevolucion({{ $grupo->id }})"
                                class="text-xs text-gray-400 hover:text-gray-600 underline">
                                Reemplazar
                            </button>
                        </div>
                    @else
                        <button wire:click="abrirSubidaDevolucion({{ $grupo->id }})"
                            class="flex items-center gap-1.5 px-3 py-2 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Subir devolución
                        </button>
                    @endif

                    @if(!$finalizado)
                        <button
                            wire:click="finalizarGrupo({{ $grupo->id }})"
                            wire:confirm="¿Finalizar el grupo {{ $grupo->nombre }}? Los alumnos verán un mensaje de cierre."
                            class="px-3 py-2 text-xs bg-gray-700 text-white rounded-lg hover:bg-gray-800">
                            Finalizar grupo
                        </button>
                    @else
                        <button
                            wire:click="reactivarGrupo({{ $grupo->id }})"
                            class="px-3 py-2 text-xs bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Reactivar
                        </button>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-gray-100">

                {{-- Columna 1: Condición por alumno --}}
                <div class="px-6 py-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Condición por alumno</h4>
                    <div class="space-y-3">
                        @foreach($grupo->usuarios as $alumno)
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-sm text-gray-700">{{ $alumno->nombre_completo }}</span>
                                <div class="flex items-center gap-1.5">
                                    <select
                                        wire:model="condicion.{{ $grupo->id }}.{{ $alumno->id }}"
                                        class="text-xs rounded border-gray-300 focus:border-indigo-500 py-1">
                                        <option value="">— Sin definir —</option>
                                        <option value="libre">Libre</option>
                                        <option value="regular">Regular</option>
                                        <option value="promocionado">Promocionado</option>
                                    </select>
                                    <button
                                        wire:click="guardarCondicion({{ $grupo->id }}, {{ $alumno->id }})"
                                        class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                        ✓
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Columna 2: Documentos --}}
                <div class="px-6 py-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">
                        Documentos
                        <span class="font-normal text-gray-400">({{ $docs_entregados->count() }}/{{ $docs_caso->count() }})</span>
                    </h4>
                    @if($docs_caso->isEmpty())
                        <p class="text-xs text-gray-400">Sin documentos en el caso.</p>
                    @else
                        <div class="space-y-1.5">
                            @foreach($docs_caso as $doc)
                                @php $entregado = $docs_entregados->contains($doc->id) @endphp
                                <div class="flex items-center gap-2 text-xs">
                                    @if($entregado)
                                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-gray-700">{{ $doc->codigo }} — {{ $doc->titulo }}</span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="text-gray-400">{{ $doc->codigo }} — {{ $doc->titulo }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Columna 3: Entrevistas --}}
                <div class="px-6 py-4">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">
                        Entrevistas
                        <span class="font-normal text-gray-400">({{ $ents_realizadas->count() }}/{{ $ents_caso->count() }})</span>
                    </h4>
                    @if($ents_caso->isEmpty())
                        <p class="text-xs text-gray-400">Sin entrevistados en el caso.</p>
                    @else
                        <div class="space-y-1.5">
                            @foreach($ents_caso as $ent)
                                @php $realizada = $ents_realizadas->contains($ent->id) @endphp
                                <div class="flex items-center gap-2 text-xs">
                                    @if($realizada)
                                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-gray-700">{{ $ent->nombre }} — {{ $ent->cargo }}</span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span class="text-gray-400">{{ $ent->nombre }} — {{ $ent->cargo }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>

    @empty
        <div class="bg-white rounded-lg shadow p-10 text-center text-gray-400 text-sm">
            No hay grupos en este estado.
        </div>
    @endforelse
</div>
