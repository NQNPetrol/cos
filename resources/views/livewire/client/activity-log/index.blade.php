<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Activity Log') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">
            {{ __('Registro de tu actividad en el sistema') }}
        </flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- Filters --}}
    <div class="mb-6 bg-zinc-100 dark:bg-zinc-800 rounded-lg p-4 border border-zinc-300 dark:border-zinc-600">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Search --}}
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    :placeholder="__('Buscar por descripción...')"
                    icon="magnifying-glass"
                />
            </div>

            {{-- Log Type Filter --}}
            <div>
                <select wire:model.live="logType" class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 focus:border-blue-500 focus:ring-blue-500 text-sm text-zinc-800">
                    <option value="">{{ __('Todos los tipos') }}</option>
                    @foreach ($logTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date From --}}
            <div>
                <input 
                    type="date" 
                    wire:model.live="dateFrom" 
                    class="w-full rounded-md border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200 focus:border-blue-500 focus:ring-blue-500 text-sm text-zinc-800"
                    placeholder="{{ __('Desde') }}"
                />
            </div>
        </div>

        <div class="mt-4 flex items-center justify-end">
            @if ($search || $logType || $dateFrom || $dateTo)
                <flux:button variant="ghost" wire:click="clearFilters" size="sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ __('Limpiar filtros') }}
                </flux:button>
            @endif
        </div>
    </div>

    {{-- Activity Table --}}
    <div class="bg-zinc-100 dark:bg-zinc-800 rounded-lg border border-zinc-300 dark:border-zinc-600 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-300 dark:divide-zinc-600">
                <thead class="bg-zinc-200 dark:bg-zinc-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            {{ __('Fecha/Hora') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            {{ __('Tipo') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            {{ __('Descripción') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                            {{ __('Detalles') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-zinc-50 dark:bg-zinc-800 divide-y divide-zinc-300 dark:divide-zinc-600">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-zinc-200 dark:hover:bg-zinc-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-800 dark:text-zinc-300">
                                <div>{{ $activity->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-zinc-600 dark:text-zinc-400">{{ $activity->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    {{ ucfirst($activity->log_name ?? 'default') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-900 dark:text-zinc-100">
                                {{ $activity->description }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-700 dark:text-zinc-400">
                                @if ($activity->properties && $activity->properties->count() > 0)
                                    <button 
                                        x-data="{ open: false }" 
                                        @click="open = !open"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-xs"
                                    >
                                        <span x-show="!open">{{ __('Ver detalles') }}</span>
                                        <span x-show="open">{{ __('Ocultar') }}</span>
                                    </button>
                                    <div x-show="open" x-transition class="mt-2 text-xs bg-zinc-200 dark:bg-zinc-700 p-2 rounded max-w-xs overflow-auto text-zinc-800 dark:text-zinc-200">
                                        <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                @else
                                    <span class="text-zinc-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center bg-zinc-50 dark:bg-zinc-800">
                                <svg class="mx-auto h-12 w-12 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-zinc-800 dark:text-zinc-100">{{ __('No hay registros de actividad') }}</h3>
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ __('Los registros de actividad aparecerán aquí cuando se generen.') }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($activities->hasPages())
            <div class="px-6 py-4 border-t border-zinc-300 dark:border-zinc-600">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</section>
