<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-cyan-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-status class="mb-4" />
            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <form method="GET" action="{{ route('admin.treks.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-1">
                            <div class="w-full">
                                <x-input-label for="q" value="Buscar" />
                                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full" value="{{ $search }}" placeholder="Nombre o código" />
                            </div>
                            <div class="flex gap-2">
                                <x-primary-button type="submit">
                                    Buscar
                                </x-primary-button>
                                @if($search !== '')
                                    <a href="{{ route('admin.treks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                        Limpiar
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div>
                            <a href="{{ route('admin.treks.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800">
                                Nueva excursión
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-sky-900 bg-sky-50 border-b border-sky-100">
                                <tr>
                                    <th class="py-2 pr-4">Código</th>
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Municipio</th>
                                    <th class="py-2 pr-4">Isla</th>
                                    <th class="py-2 pr-4">Estado</th>
                                    <th class="py-2 pr-4">Lugares</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($treks as $trek)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $trek->regnumber }}</td>
                                        <td class="py-2 pr-4">{{ $trek->name }}</td>
                                        <td class="py-2 pr-4">{{ $trek->municipality?->name }}</td>
                                        <td class="py-2 pr-4">{{ $trek->municipality?->island?->name ?? '-' }}</td>
                                        <td class="py-2 pr-4">
                                            @if ($trek->status === 'y')
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-green-700 bg-green-100 rounded-full">
                                                    Activa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-red-700 bg-red-100 rounded-full">
                                                    Inactiva
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">{{ $trek->interesting_places_count }}</td>
                                        <td class="py-2 pr-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('admin.treks.show', $trek->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-green-700 rounded-md hover:bg-green-600">
                                                    Ver
                                                </a>
                                                <a href="{{ route('admin.treks.edit', $trek->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-6 text-center text-gray-500">
                                            No hay excursiones para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        Mostrando {{ $treks->firstItem() ?? 0 }} a {{ $treks->lastItem() ?? 0 }} de {{ $treks->total() }} resultados
                    </div>
                </div>
            </div>

            <div class="mt-2 flex justify-end">
                {{ $treks->links('admin.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
