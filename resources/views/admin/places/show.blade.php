<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-emerald-50 via-lime-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">
            <div class="flex justify-between items-center bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full">Lugar</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.places.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.places.edit', $place->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><dt class="text-gray-500">Nombre</dt><dd class="font-medium">{{ $place->name }}</dd></div>
                    <div class="rounded-lg border border-lime-100 bg-lime-50/50 p-3"><dt class="text-gray-500">Tipo</dt><dd class="font-medium">{{ $place->placeType?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-lime-100 bg-lime-50/50 p-3"><dt class="text-gray-500">GPS</dt><dd class="font-medium">{{ $place->gps }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/50 p-3"><dt class="text-gray-500">Latitud</dt><dd class="font-medium">{{ $place->latitude ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/50 p-3"><dt class="text-gray-500">Longitud</dt><dd class="font-medium">{{ $place->longitude ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/50 p-3"><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $place->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/50 p-3"><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $place->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-lime-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-lime-900 mb-3">Excursiones donde aparece</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-lime-900 bg-lime-50 border-b border-lime-100">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Código</th>
                                <th class="py-2 pr-4">Excursión</th>
                                <th class="py-2 pr-4">Municipio</th>
                                <th class="py-2 pr-4">Orden en ruta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-lime-50">
                            @forelse ($place->treks as $trek)
                                <tr class="hover:bg-lime-50/40">
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
