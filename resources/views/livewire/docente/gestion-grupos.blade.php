<div>
    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Grupos de trabajo</h2>
        <button wire:click="abrirModal()"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Nuevo grupo
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caso</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumnos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($grupos as $grupo)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $grupo->nombre }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $grupo->caso->nombre }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @foreach ($grupo->usuarios as $usuario)
                                <span class="block">{{ $usuario->nombre_completo }}</span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $grupo->estado === 'activo' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $grupo->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $grupo->estado === 'finalizado' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ ucfirst($grupo->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="abrirModal({{ $grupo->id }})"
                                class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                                Editar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No hay grupos creados todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($mostrarModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ $editando_grupo ? 'Editar grupo' : 'Nuevo grupo' }}
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre del grupo</label>
                        <input type="text" wire:model="nombre"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500"
                            placeholder="Grupo 01">
                        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Caso asignado</label>
                        <select wire:model="caso_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500">
                            <option value="">Seleccionar caso...</option>
                            @foreach ($casos as $caso)
                                <option value="{{ $caso->id }}">{{ $caso->nombre }}</option>
                            @endforeach
                        </select>
                        @error('caso_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Alumnos (máximo 3)
                        </label>
                        @forelse ($alumnos_disponibles as $alumno)
                            <label class="flex items-center gap-2 mt-2">
                                <input type="checkbox"
                                    wire:model="alumnos_seleccionados"
                                    value="{{ $alumno->id }}"
                                    class="rounded border-gray-300">
                                {{ $alumno->nombre_completo }}
                                @if (in_array((string)$alumno->id, $alumnos_seleccionados))
                                    <span class="text-xs text-indigo-500">(en este grupo)</span>
                                @endif
                            </label>
                        @empty
                            <p class="text-sm text-gray-500 mt-1">No hay alumnos disponibles.</p>
                        @endforelse
                        @error('alumnos_seleccionados') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="cerrarModal"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button wire:click="guardarGrupo"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        {{ $editando_grupo ? 'Guardar cambios' : 'Crear grupo' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>