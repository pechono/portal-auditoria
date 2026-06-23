<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('mensaje') }}</div>
    @endif

    <div class="flex gap-6">

        {{-- Sidebar: ciclos --}}
        <div class="w-64 shrink-0">
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-800">Ciclos lectivos</h3>
                    <button wire:click="abrirModalCiclo()"
                        class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        + Nuevo
                    </button>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($ciclos as $c)
                        <div wire:click="seleccionarCiclo({{ $c->id }})"
                            class="px-4 py-3 cursor-pointer hover:bg-gray-50 {{ $ciclo_id === $c->id ? 'bg-indigo-50 border-l-4 border-indigo-500' : '' }}">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $c->nombre }}</p>
                                    <p class="text-xs text-gray-400">{{ $c->anio }}</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    @if ($c->activo)
                                        <span class="text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full">Activo</span>
                                    @endif
                                    <button wire:click.stop="abrirModalCiclo({{ $c->id }})"
                                        class="text-gray-400 hover:text-gray-600 text-xs">✎</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="px-4 py-3 text-xs text-gray-400">Sin ciclos creados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel principal --}}
        <div class="flex-1 min-w-0">
            @if (!$ciclo)
                <div class="bg-white rounded-lg shadow p-10 text-center text-gray-400">
                    Seleccioná o creá un ciclo lectivo para comenzar.
                </div>
            @else
                {{-- Header ciclo --}}
                <div class="bg-white rounded-lg shadow px-6 py-4 mb-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $ciclo->nombre }}</h2>
                        @if ($ciclo->observaciones)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $ciclo->observaciones }}</p>
                        @endif
                    </div>
                    <button wire:click="abrirModalTrabajo()"
                        class="px-3 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        + Agregar columna
                    </button>
                </div>

                @if ($trabajos->isEmpty())
                    <div class="bg-white rounded-lg shadow p-8 text-center text-gray-400">
                        Todavía no hay trabajos para este ciclo. Agregá una columna para empezar (ej: "TP1", "Parcial Teórico").
                    </div>
                @elseif ($alumnos->isEmpty())
                    <div class="bg-white rounded-lg shadow p-8 text-center text-gray-400">
                        No hay alumnos registrados.
                    </div>
                @else
                    {{-- Grilla de notas --}}
                    <div class="bg-white rounded-lg shadow overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 sticky left-0 bg-gray-50 z-10 min-w-[180px]">
                                        Alumno
                                    </th>
                                    @foreach ($trabajos as $trabajo)
                                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-600 min-w-[130px]">
                                            <div class="flex flex-col items-center gap-1">
                                                <span>{{ $trabajo->nombre }}</span>
                                                <div class="flex gap-1">
                                                    <button wire:click="abrirModalTrabajo({{ $trabajo->id }})"
                                                        class="text-gray-400 hover:text-indigo-600 text-xs">✎</button>
                                                    <button wire:click="eliminarTrabajo({{ $trabajo->id }})"
                                                        wire:confirm="¿Eliminar '{{ $trabajo->nombre }}' y todas sus notas?"
                                                        class="text-gray-400 hover:text-red-600 text-xs">✕</button>
                                                </div>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($alumnos as $alumno)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 font-medium text-gray-900 sticky left-0 bg-white z-10">
                                            {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                        </td>
                                        @foreach ($trabajos as $trabajo)
                                            <td class="px-3 py-2 text-center">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    max="10"
                                                    wire:model.lazy="notas.{{ $trabajo->id }}.{{ $alumno->id }}"
                                                    wire:change="guardarNota({{ $trabajo->id }}, {{ $alumno->id }})"
                                                    placeholder="—"
                                                    class="w-20 text-center rounded border-gray-300 text-sm focus:border-indigo-400 focus:ring-indigo-400
                                                        @php
                                                            $n = $notas[$trabajo->id][$alumno->id] ?? null;
                                                        @endphp
                                                        {{ ($n !== null && $n !== '') ? (floatval($n) >= 6 ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800') : '' }}"
                                                >
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="text-xs text-gray-400 mt-2 text-right">
                        Las notas se guardan automáticamente al salir del campo. Escala 0–10. Verde ≥ 6, rojo &lt; 6.
                    </p>
                @endif
            @endif
        </div>
    </div>

    {{-- Modal ciclo --}}
    @if ($mostrarModalCiclo)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_ciclo ? 'Editar ciclo' : 'Nuevo ciclo lectivo' }}
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" wire:model="ciclo_nombre"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: 2026, 2026 - 1er cuatrimestre">
                        @error('ciclo_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                        <input type="number" wire:model="ciclo_anio"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="2026">
                        @error('ciclo_anio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones <span class="text-gray-400 font-normal">(opcional)</span></label>
                        <textarea wire:model="ciclo_obs" rows="2"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Notas adicionales sobre este ciclo..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('mostrarModalCiclo', false)"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarCiclo"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_ciclo ? 'Guardar cambios' : 'Crear ciclo' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal trabajo evaluable --}}
    @if ($mostrarModalTrabajo)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_trabajo ? 'Editar columna' : 'Nueva columna de evaluación' }}
                </h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" wire:model="trabajo_nombre"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                        placeholder="Ej: TP1, Parcial Teórico, Defensa Final">
                    @error('trabajo_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('mostrarModalTrabajo', false)"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarTrabajo"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_trabajo ? 'Guardar' : 'Agregar' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
