<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg font-medium">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Alerta de alumnos sin grupo --}}
    @if($sin_grupo > 0 && !$solo_sin_grupo)
        <div class="mb-4 flex items-center gap-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
            <p class="text-sm text-amber-700 flex-1">
                <span class="font-semibold">{{ $sin_grupo }} {{ $sin_grupo === 1 ? 'alumno' : 'alumnos' }}</span>
                {{ $sin_grupo === 1 ? 'no tiene' : 'no tienen' }} grupo asignado.
            </p>
            <button wire:click="$set('solo_sin_grupo', true)"
                class="text-xs text-amber-700 font-medium underline hover:text-amber-900 whitespace-nowrap">
                Ver solo estos
            </button>
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
        <div class="flex gap-2 flex-1">
            <div class="relative flex-1 max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" wire:model.live="buscar"
                    class="block w-full pl-9 rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500"
                    placeholder="Buscar alumno...">
            </div>
            <button wire:click="$toggle('solo_sin_grupo')"
                class="flex items-center gap-1.5 px-3 py-2 text-sm rounded-lg border transition
                    {{ $solo_sin_grupo ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-amber-50 hover:border-amber-300 hover:text-amber-700' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Sin grupo
                @if($sin_grupo > 0)
                    <span class="px-1.5 py-0.5 text-xs rounded-full leading-none
                        {{ $solo_sin_grupo ? 'bg-white text-amber-600' : 'bg-amber-100 text-amber-700' }}">
                        {{ $sin_grupo }}
                    </span>
                @endif
            </button>
        </div>
        <button wire:click="abrirModal()"
            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
            + Nuevo alumno
        </button>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($alumnos as $alumno)
                    @php $tiene_grupo = $alumno->grupos->count() > 0 @endphp
                    <tr class="{{ !$tiene_grupo ? 'bg-amber-50' : 'hover:bg-gray-50' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-900">{{ $alumno->nombre_completo }}</p>
                                @if(!$tiene_grupo)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-amber-100 text-amber-700 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                        </svg>
                                        Sin grupo
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $alumno->email }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if($tiene_grupo)
                                <div>
                                    <p class="text-sm text-gray-800">{{ $alumno->grupos->first()->nombre }}</p>
                                    <p class="text-xs text-gray-400">{{ $alumno->grupos->first()->caso->nombre }}</p>
                                </div>
                            @else
                                <span class="text-xs text-amber-600 italic">No asignado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button wire:click="abrirModal({{ $alumno->id }})"
                                    class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                                    Editar
                                </button>
                                <button wire:click="resetearPassword({{ $alumno->id }})"
                                    wire:confirm="¿Resetear la contraseña de {{ $alumno->nombre_completo }}?"
                                    class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded hover:bg-yellow-200">
                                    Resetear contraseña
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            @if($solo_sin_grupo)
                                Todos los alumnos tienen grupo asignado.
                            @else
                                No hay alumnos registrados todavía.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_alumno ? 'Editar alumno' : 'Nuevo alumno' }}
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" wire:model="nombre"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Ej: Juan">
                            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                            <input type="text" wire:model="apellido"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                                placeholder="Ej: Pérez">
                            @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" wire:model="email"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Ej: juan.perez@gmail.com">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if (!$editando_alumno)
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="text-xs text-blue-700">
                                Se generará una contraseña temporal automáticamente.
                                Aparecerá en pantalla al guardar para que se la entregues al alumno.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarAlumno"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_alumno ? 'Guardar cambios' : 'Crear alumno' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>