<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-indigo-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">
            <div class="flex justify-between items-center bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-sky-700 bg-sky-100 rounded-full">Encuentro</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.meetings.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.meetings.edit', $meeting->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div class="rounded-lg border border-sky-100 bg-sky-50/40 p-3"><dt class="text-gray-500">Excursión</dt><dd class="font-medium">{{ $meeting->trek?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-sky-100 bg-sky-50/40 p-3"><dt class="text-gray-500">Código excursión</dt><dd class="font-medium">{{ $meeting->trek?->regnumber ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-indigo-100 bg-indigo-50/40 p-3"><dt class="text-gray-500">Municipio</dt><dd class="font-medium">{{ $meeting->trek?->municipality?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-indigo-100 bg-indigo-50/40 p-3"><dt class="text-gray-500">Isla</dt><dd class="font-medium">{{ $meeting->trek?->municipality?->island?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-indigo-100 bg-indigo-50/40 p-3"><dt class="text-gray-500">Guía principal</dt><dd class="font-medium">{{ $meeting->user?->name }} {{ $meeting->user?->lastname }}</dd></div>
                    <div class="rounded-lg border border-indigo-100 bg-indigo-50/40 p-3"><dt class="text-gray-500">Rol guía principal</dt><dd class="font-medium">{{ $meeting->user?->role?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/40 p-3"><dt class="text-gray-500">Día</dt><dd class="font-medium">{{ $meeting->day_formatted }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/40 p-3"><dt class="text-gray-500">Hora</dt><dd class="font-medium">{{ $meeting->hour }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/40 p-3"><dt class="text-gray-500">Inicio inscripción</dt><dd class="font-medium">{{ $meeting->app_date_ini_formatted }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/40 p-3"><dt class="text-gray-500">Fin inscripción</dt><dd class="font-medium">{{ $meeting->app_date_end_formatted }}</dd></div>
                    <div class="rounded-lg border border-teal-100 bg-teal-50/40 p-3"><dt class="text-gray-500">Inscripción abierta</dt><dd class="font-medium">{{ $meeting->enrollment_is_open ? 'Sí' : 'No' }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">Total puntuación</dt><dd class="font-medium">{{ $meeting->totalScore }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">N.º puntuaciones</dt><dd class="font-medium">{{ $meeting->countScore }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $meeting->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $meeting->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-indigo-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-indigo-900 mb-3">Guías adicionales</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($extraGuides as $guide)
                        <li class="border border-indigo-100 bg-indigo-50/40 rounded-md px-3 py-2">
                            #{{ $guide->id }} - {{ $guide->name }} {{ $guide->lastname }} ({{ $guide->email }})
                        </li>
                    @empty
                        <li class="text-gray-500">Sin guías adicionales.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-sky-900 mb-3">Asistentes</h3>
                <ul class="space-y-2 text-sm">
                    @forelse ($attendees as $attendee)
                        <li class="border border-sky-100 bg-sky-50/40 rounded-md px-3 py-2">
                            #{{ $attendee->id }} - {{ $attendee->name }} {{ $attendee->lastname }} ({{ $attendee->email }})
                        </li>
                    @empty
                        <li class="text-gray-500">Sin asistentes.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white/90 border border-teal-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-teal-900 mb-3">Comentarios</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <caption class="sr-only">Tabla de datos</caption>
                        <thead class="text-left text-teal-900 bg-teal-50 border-b border-teal-100">
                            <tr>
                                <th scope="col" class="py-2 pr-4">ID</th>
                                <th scope="col" class="py-2 pr-4">Usuario</th>
                                <th scope="col" class="py-2 pr-4">Puntuación</th>
                                <th scope="col" class="py-2 pr-4">Estado</th>
                                <th scope="col" class="py-2 pr-4">Imágenes</th>
                                <th scope="col" class="py-2 pr-4">Texto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-teal-50">
                            @forelse ($meeting->comments as $comment)
                                <tr class="hover:bg-teal-50/40">
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
