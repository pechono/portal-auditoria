<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg font-medium">
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="flex justify-between items-center mb-6">
        <input type="text" wire:model.live="buscar"
            class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500"
            placeholder="Buscar alumno...">
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
                    <tr>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $alumno->nombre_completo }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $alumno->email }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @if ($alumno->grupos->count() > 0)
                                {{ $alumno->grupos->first()->nombre }} —
                                {{ $alumno->grupos->first()->caso->nombre }}
                            @else
                                <span class="text-xs text-yellow-600">Sin grupo</span>
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
                            No hay alumnos registrados todavía.
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