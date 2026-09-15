<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Usuarios</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Tabs internos --}}
            @php $tab = request('tab', 'alumnos'); @endphp
            <div class="flex gap-1 mb-6 border-b border-gray-200">
                <a href="{{ route('docente.usuarios', ['tab' => 'alumnos']) }}"
                    class="px-5 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition
                        {{ $tab === 'alumnos'
                            ? 'border-indigo-600 text-indigo-600 bg-white'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Alumnos
                </a>
                <a href="{{ route('docente.usuarios', ['tab' => 'grupos']) }}"
                    class="px-5 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition
                        {{ $tab === 'grupos'
                            ? 'border-indigo-600 text-indigo-600 bg-white'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Grupos
                </a>
                <a href="{{ route('docente.usuarios', ['tab' => 'docentes']) }}"
                    class="px-5 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition
                        {{ $tab === 'docentes'
                            ? 'border-indigo-600 text-indigo-600 bg-white'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Docentes
                </a>
            </div>

            {{-- Contenido según tab --}}
            @if($tab === 'alumnos')
                <livewire:docente.gestion-alumnos />
            @elseif($tab === 'grupos')
                <livewire:docente.gestion-grupos />
            @else
                <livewire:docente.gestion-docentes />
            @endif

        </div>
    </div>
</x-app-layout>
