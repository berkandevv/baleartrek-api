<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-cyan-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash-status class="mb-4" />
            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <form method="GET" action="{{ route('admin.municipalities.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-1">
                            <div class="w-full">
                                <x-input-label for="q" value="Buscar" />
                                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full" value="{{ $search }}" placeholder="Nombre del municipio" />
                            </div>
                            <div class="flex gap-2">
                                <x-primary-button type="submit">
                                    Buscar
                                </x-primary-button>
                                @if($search !== '')
                                    <a href="{{ route('admin.municipalities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                        Limpiar
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div>
                            <a href="{{ route('admin.municipalities.create') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800">
                                Nuevo municipio
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-sky-900 bg-sky-50 border-b border-sky-100">
                                <tr>
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Zona</th>
                                    <th class="py-2 pr-4">Isla</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($municipalities as $municipality)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $municipality->name }}</td>
                                        <td class="py-2 pr-4">{{ $municipality->zone?->name }}</td>
                                        <td class="py-2 pr-4">{{ $municipality->island?->name }}</td>
                                        <td class="py-2 pr-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('admin.municipalities.show', $municipality->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-green-700 rounded-md hover:bg-green-600">
                                                    Ver
                                                </a>
                                                <a href="{{ route('admin.municipalities.edit', $municipality->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-gray-500">
                                            No hay municipios para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 text-sm text-gray-500">
                        Mostrando {{ $municipalities->firstItem() ?? 0 }} a {{ $municipalities->lastItem() ?? 0 }} de {{ $municipalities->total() }} resultados
                    </div>
                </div>
            </div>

            <div class="mt-2 flex justify-end">
                {{ $municipalities->links('admin.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
