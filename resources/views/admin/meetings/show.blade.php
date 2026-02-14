<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900">Detalle de encuentro #{{ $meeting->id }}</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.meetings.edit', $meeting->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div><dt class="text-gray-500">Excursión</dt><dd class="font-medium">{{ $meeting->trek?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Código excursión</dt><dd class="font-medium">{{ $meeting->trek?->regnumber ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Municipio</dt><dd class="font-medium">{{ $meeting->trek?->municipality?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Isla</dt><dd class="font-medium">{{ $meeting->trek?->municipality?->island?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Guía principal</dt><dd class="font-medium">{{ $meeting->user?->name }} {{ $meeting->user?->lastname }}</dd></div>
                    <div><dt class="text-gray-500">Rol guía principal</dt><dd class="font-medium">{{ $meeting->user?->role?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Día</dt><dd class="font-medium">{{ $meeting->day_formatted }}</dd></div>
                    <div><dt class="text-gray-500">Hora</dt><dd class="font-medium">{{ $meeting->hour }}</dd></div>
                    <div><dt class="text-gray-500">Inicio inscripción</dt><dd class="font-medium">{{ $meeting->app_date_ini_formatted }}</dd></div>
                    <div><dt class="text-gray-500">Fin inscripción</dt><dd class="font-medium">{{ $meeting->app_date_end_formatted }}</dd></div>
                    <div><dt class="text-gray-500">Inscripción abierta</dt><dd class="font-medium">{{ $meeting->enrollment_is_open ? 'Sí' : 'No' }}</dd></div>
                    <div><dt class="text-gray-500">Total puntuación</dt><dd class="font-medium">{{ $meeting->totalScore }}</dd></div>
                    <div><dt class="text-gray-500">N.º puntuaciones</dt><dd class="font-medium">{{ $meeting->countScore }}</dd></div>
                    <div><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $meeting->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $meeting->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Guías adicionales</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($extraGuides as $guide)
                        <li class="border border-slate-200 rounded-md px-3 py-2">
                            #{{ $guide->id }} - {{ $guide->name }} {{ $guide->lastname }} ({{ $guide->email }})
                        </li>
                    @empty
                        <li class="text-gray-500">Sin guías adicionales.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Asistentes</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($attendees as $attendee)
                        <li class="border border-slate-200 rounded-md px-3 py-2">
                            #{{ $attendee->id }} - {{ $attendee->name }} {{ $attendee->lastname }} ({{ $attendee->email }})
                        </li>
                    @empty
                        <li class="text-gray-500">Sin asistentes.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Comentarios</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Usuario</th>
                                <th class="py-2 pr-4">Puntuación</th>
                                <th class="py-2 pr-4">Estado</th>
                                <th class="py-2 pr-4">Imágenes</th>
                                <th class="py-2 pr-4">Texto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($meeting->comments as $comment)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">#{{ $comment->id }}</td>
                                    <td class="py-2 pr-4">{{ $comment->user?->name }} {{ $comment->user?->lastname }}</td>
                                    <td class="py-2 pr-4">{{ $comment->score }}</td>
                                    <td class="py-2 pr-4">{{ $comment->status === 'y' ? 'Aprobado' : 'Pendiente' }}</td>
                                    <td class="py-2 pr-4">{{ $comment->images_count }}</td>
                                    <td class="py-2 pr-4">{{ $comment->comment }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-gray-500">Sin comentarios.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
