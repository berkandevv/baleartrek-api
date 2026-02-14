<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900">Detalle de lugar #{{ $place->id }}</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.places.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.places.edit', $place->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div><dt class="text-gray-500">Nombre</dt><dd class="font-medium">{{ $place->name }}</dd></div>
                    <div><dt class="text-gray-500">Tipo</dt><dd class="font-medium">{{ $place->placeType?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">GPS</dt><dd class="font-medium">{{ $place->gps }}</dd></div>
                    <div><dt class="text-gray-500">Latitud</dt><dd class="font-medium">{{ $place->latitude ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Longitud</dt><dd class="font-medium">{{ $place->longitude ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $place->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $place->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Excursiones donde aparece</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Código</th>
                                <th class="py-2 pr-4">Excursión</th>
                                <th class="py-2 pr-4">Municipio</th>
                                <th class="py-2 pr-4">Orden en ruta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($place->treks as $trek)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">#{{ $trek->id }}</td>
                                    <td class="py-2 pr-4">{{ $trek->regnumber }}</td>
                                    <td class="py-2 pr-4">{{ $trek->name }}</td>
                                    <td class="py-2 pr-4">{{ $trek->municipality?->name ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $trek->pivot?->order }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-gray-500">No está asociado a excursiones.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
