<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900">Detalle de excursión #{{ $trek->id }}</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.treks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.treks.edit', $trek->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6 space-y-4">
                @if ($trek->image_display_url)
                    <img src="{{ $trek->image_display_url }}" alt="{{ $trek->name }}" class="w-full max-w-xl rounded-lg border border-slate-200">
                @endif

                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div><dt class="text-gray-500">Código</dt><dd class="font-medium">{{ $trek->regnumber }}</dd></div>
                    <div><dt class="text-gray-500">Nombre</dt><dd class="font-medium">{{ $trek->name }}</dd></div>
                    <div><dt class="text-gray-500">Estado</dt><dd class="font-medium">{{ $trek->status === 'y' ? 'Activa' : 'Inactiva' }}</dd></div>
                    <div><dt class="text-gray-500">Municipio</dt><dd class="font-medium">{{ $trek->municipality?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Zona</dt><dd class="font-medium">{{ $trek->municipality?->zone?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Isla</dt><dd class="font-medium">{{ $trek->municipality?->island?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Total puntuación</dt><dd class="font-medium">{{ $trek->totalScore }}</dd></div>
                    <div><dt class="text-gray-500">N.º puntuaciones</dt><dd class="font-medium">{{ $trek->countScore }}</dd></div>
                    <div><dt class="text-gray-500">Creada</dt><dd class="font-medium">{{ $trek->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Actualizada</dt><dd class="font-medium">{{ $trek->updated_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="md:col-span-2">
                        <dt class="text-gray-500">Descripción</dt>
                        <dd class="font-medium prose max-w-none">{!! $trek->description ?: '<span class="text-gray-400">Sin descripción</span>' !!}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Lugares remarcables</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">Orden</th>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Nombre</th>
                                <th class="py-2 pr-4">Tipo</th>
                                <th class="py-2 pr-4">GPS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trek->interestingPlaces as $place)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">{{ $place->pivot?->order }}</td>
                                    <td class="py-2 pr-4">#{{ $place->id }}</td>
                                    <td class="py-2 pr-4">{{ $place->name }}</td>
                                    <td class="py-2 pr-4">{{ $place->placeType?->name ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $place->gps }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-gray-500">No hay lugares asociados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Encuentros</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Guía</th>
                                <th class="py-2 pr-4">Día</th>
                                <th class="py-2 pr-4">Hora</th>
                                <th class="py-2 pr-4">Inscripción</th>
                                <th class="py-2 pr-4">Comentarios</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($trek->meetings as $meeting)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">#{{ $meeting->id }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->user?->name }} {{ $meeting->user?->lastname }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->day_formatted }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->hour }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->app_date_ini_formatted }} - {{ $meeting->app_date_end_formatted }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->comments_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-gray-500">No hay encuentros asociados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
