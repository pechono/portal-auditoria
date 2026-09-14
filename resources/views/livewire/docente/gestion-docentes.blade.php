<div>
    @if(session('mensaje'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('mensaje') }}</div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">Docentes con acceso al panel.</p>
        <button wire:click="abrirModal"
            class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            + Nuevo docente
        </button>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Creado</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($docentes as $doc)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium text-gray-800">
                            {{ $doc->apellido }}, {{ $doc->nombre }}
                            @if($doc->id === auth()->id())
                                <span class="ml-2 text-xs text-indigo-400">(vos)</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $doc->email }}</td>
                        <td class="px-6 py-3 text-gray-400">{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-right">
                            @if($doc->id !== auth()->id())
                                <button wire:click="eliminar({{ $doc->id }})"
                                    wire:confirm="¿Eliminar al docente {{ $doc->nombre }} {{ $doc->apellido }}?"
                                    class="text-xs text-red-400 hover:text-red-600">Eliminar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">Sin docentes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($mostrarModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
                <div class="flex items-center justify-between px-6 py-4 bg-indigo-600 rounded-t-xl">
                    <h3 class="text-base font-semibold text-white">Nuevo docente</h3>
                    <button wire:click="$set('mostrarModal', false)" class="text-white text-xl">&times;</button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" wire:model="nombre"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500">
                            @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Apellido *</label>
                            <input type="text" wire:model="apellido"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500">
                            @error('apellido') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" wire:model="email"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Contraseña * <span class="text-gray-400 font-normal">(mín. 8 caracteres)</span></label>
                        <input type="password" wire:model="password"
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="px-6 pb-5 flex justify-end gap-3">
                    <button wire:click="$set('mostrarModal', false)"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                    <button wire:click="guardar"
                        class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Crear docente</button>
                </div>
            </div>
        </div>
    @endif
</div>
