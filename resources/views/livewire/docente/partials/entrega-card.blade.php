@php
    $pct       = $grupo->_pct;
    $color     = $pct >= 80 ? 'bg-green-500' : ($pct >= 50 ? 'bg-yellow-400' : 'bg-red-400');
    $textColor = $pct >= 80 ? 'text-green-700' : ($pct >= 50 ? 'text-yellow-700' : 'text-red-600');
    $tieneNovedades = $grupo->_pendientes > 0 || $grupo->_con_obs > 0 || $grupo->_rechazadas > 0;
@endphp

<div class="bg-white rounded-xl shadow mb-3 overflow-hidden"
    x-data="{ open: {{ $autoOpen ? 'true' : 'false' }} }">

    {{-- Cabecera clicable --}}
    <div @click="open = !open"
        class="px-5 py-4 cursor-pointer select-none
            {{ $grupo->estado === 'finalizado' ? 'bg-gray-50' : 'bg-indigo-50' }}
            hover:brightness-95 transition">

        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                {{-- Chevron --}}
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200"
                    :class="open ? 'rotate-90' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>

                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-sm font-semibold {{ $grupo->estado === 'finalizado' ? 'text-gray-500' : 'text-indigo-800' }}">
                            {{ $grupo->nombre }}
                        </h3>
                        @if($grupo->estado === 'finalizado')
                            <span class="px-2 py-0.5 text-xs bg-gray-200 text-gray-500 rounded-full">Finalizado</span>
                        @endif
                        {{-- Badges de novedades --}}
                        @if($grupo->_pendientes > 0)
                            <span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded-full font-medium">
                                {{ $grupo->_pendientes }} pendiente{{ $grupo->_pendientes > 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if($grupo->_con_obs > 0)
                            <span class="px-2 py-0.5 text-xs bg-orange-100 text-orange-700 rounded-full">
                                {{ $grupo->_con_obs }} con obs.
                            </span>
                        @endif
                        @if($grupo->_rechazadas > 0)
                            <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full">
                                {{ $grupo->_rechazadas }} rechazada{{ $grupo->_rechazadas > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5 truncate">
                        {{ $grupo->caso->nombre }} —
                        {{ $grupo->usuarios->map(fn($u) => $u->apellido)->join(', ') }}
                    </p>
                </div>
            </div>

            {{-- Progreso + acciones --}}
            <div class="flex items-center gap-4 flex-shrink-0 ml-4">
                <div class="text-right hidden sm:block">
                    <span class="text-sm font-bold {{ $textColor }}">{{ $pct }}%</span>
                    <p class="text-xs text-gray-400">Etapa {{ $grupo->_max_etapa }}/{{ $grupo->_total_etapas }}</p>
                </div>
                @if($grupo->estado !== 'finalizado')
                    <button
                        wire:click.stop="finalizarGrupo({{ $grupo->id }})"
                        wire:confirm="¿Finalizar el grupo {{ $grupo->nombre }}?"
                        class="px-3 py-1.5 text-xs bg-gray-700 text-white rounded-lg hover:bg-gray-800">
                        Finalizar
                    </button>
                @endif
            </div>
        </div>

        {{-- Barra de progreso --}}
        <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
            <div class="{{ $color }} h-1.5 rounded-full transition-all duration-500"
                style="width: {{ $pct }}%"></div>
        </div>
    </div>

    {{-- Contenido desplegable --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">

        @php
            $orden_estados  = ['enviada', 'con_observaciones', 'rechazada', 'aprobada'];
            $entregas_grupo = $grupo->entregas->sortBy('etapa.numero')->groupBy('estado');
            $config_estado  = [
                'enviada'           => ['label' => 'Pendientes de revisión', 'header' => 'bg-yellow-50 text-yellow-800 border-yellow-200'],
                'con_observaciones' => ['label' => 'Con observaciones',      'header' => 'bg-orange-50 text-orange-800 border-orange-200'],
                'rechazada'         => ['label' => 'Rechazadas',             'header' => 'bg-red-50 text-red-800 border-red-200'],
                'aprobada'          => ['label' => 'Aprobadas',              'header' => 'bg-green-50 text-green-800 border-green-200'],
            ];
        @endphp

        @if($grupo->entregas->isEmpty())
            <div class="px-6 py-4 text-sm text-gray-400 italic">Sin entregas aún.</div>
        @else
            @foreach($orden_estados as $est)
                @if($entregas_grupo->has($est))
                    <div class="border-t border-gray-100">
                        <div class="px-5 py-1.5 text-xs font-semibold {{ $config_estado[$est]['header'] }} border-b">
                            {{ $config_estado[$est]['label'] }} ({{ $entregas_grupo[$est]->count() }})
                        </div>
                        @foreach($entregas_grupo[$est] as $entrega)
                            <div class="flex items-center justify-between px-5 py-2.5 border-t border-gray-50 hover:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    @php
                                        $dot = match($entrega->estado) {
                                            'aprobada'          => 'bg-green-500',
                                            'enviada'           => 'bg-yellow-400',
                                            'con_observaciones' => 'bg-orange-400',
                                            'rechazada'         => 'bg-red-500',
                                            default             => 'bg-gray-300',
                                        };
                                    @endphp
                                    <span class="w-2 h-2 rounded-full {{ $dot }} flex-shrink-0"></span>
                                    <div>
                                        <p class="text-sm text-gray-800">
                                            {{ $entrega->etapa->numero }}. {{ $entrega->etapa->nombre }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $entrega->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @if($entrega->nota !== null)
                                        <span class="text-xs font-semibold text-indigo-600">{{ number_format($entrega->nota, 1) }}</span>
                                    @endif
                                    <a href="{{ asset('uploads/' . $entrega->archivo_path) }}" target="_blank"
                                        class="text-gray-400 hover:text-indigo-600" title="Ver archivo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                        </svg>
                                    </a>
                                    <button wire:click="abrirModal({{ $entrega->id }})"
                                        class="px-3 py-1 text-xs rounded {{ $entrega->estado === 'enviada' ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border' }}">
                                        {{ $entrega->estado === 'enviada' ? 'Revisar' : 'Editar' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
