<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-lime-50 via-emerald-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">
            <div class="flex justify-between items-center bg-white/90 border border-lime-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-lime-700 bg-lime-100 rounded-full">Municipio</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.municipalities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.municipalities.edit', $municipality->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-lime-100 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div class="rounded-lg border border-lime-100 bg-lime-50/50 p-3"><dt class="text-gray-500">Nombre</dt><dd class="font-medium">{{ $municipality->name }}</dd></div>
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><dt class="text-gray-500">Zona</dt><dd class="font-medium">{{ $municipality->zone?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/50 p-3"><dt class="text-gray-500">Isla</dt><dd class="font-medium">{{ $municipality->island?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/50 p-3"><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $municipality->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/50 p-3"><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $municipality->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-emerald-900 mb-3">Excursiones del municipio</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <caption class="sr-only">Listado de excursiones del municipio</caption>
                        <thead class="text-left text-emerald-900 bg-emerald-50 border-b border-emerald-100">
                            <tr>
                                <th scope="col" class="py-2 pr-4">ID</th>
                                <th scope="col" class="py-2 pr-4">Código</th>
                                <th scope="col" class="py-2 pr-4">Nombre</th>
                                <th scope="col" class="py-2 pr-4">Estado</th>
                                <th scope="col" class="py-2 pr-4">Encuentros</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50">
                            @forelse ($municipality->treks as $trek)
                                <tr class="hover:bg-emerald-50/40">
                                    <td class="py-2 pr-4">#{{ $trek->id }}</td>
                                    <td class="py-2 pr-4">{{ $trek->regnumber }}</td>
                                    <td class="py-2 pr-4">{{ $trek->name }}</td>
                                    <td class="py-2 pr-4">{{ $trek->status === 'y' ? 'Activa' : 'Inactiva' }}</td>
                                    <td class="py-2 pr-4">{{ $trek->meetings_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-gray-500">No hay excursiones asociadas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
