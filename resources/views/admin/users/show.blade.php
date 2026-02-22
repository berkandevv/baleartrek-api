<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-emerald-50 via-sky-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">
            <div class="flex justify-between items-center bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full">Usuario</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/40 p-3"><dt class="text-gray-500">Nombre</dt><dd class="font-medium">{{ $user->name }}</dd></div>
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/40 p-3"><dt class="text-gray-500">Apellidos</dt><dd class="font-medium">{{ $user->lastname }}</dd></div>
                    <div class="rounded-lg border border-sky-100 bg-sky-50/40 p-3"><dt class="text-gray-500">DNI</dt><dd class="font-medium">{{ $user->dni }}</dd></div>
                    <div class="rounded-lg border border-sky-100 bg-sky-50/40 p-3"><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $user->email }}</dd></div>
                    <div class="rounded-lg border border-sky-100 bg-sky-50/40 p-3"><dt class="text-gray-500">Teléfono</dt><dd class="font-medium">{{ $user->phone }}</dd></div>
                    <div class="rounded-lg border border-amber-100 bg-amber-50/40 p-3"><dt class="text-gray-500">Rol</dt><dd class="font-medium">{{ $user->role?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-amber-100 bg-amber-50/40 p-3"><dt class="text-gray-500">Estado</dt><dd class="font-medium">{{ $user->status === 'y' ? 'Alta' : 'Baja' }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">Email verificado</dt><dd class="font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('d-m-Y H:i') : 'No' }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $user->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="rounded-lg border border-violet-100 bg-violet-50/40 p-3"><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $user->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-sky-900 mb-3">Encuentros como guía principal</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <caption class="sr-only">Tabla de datos</caption>
                        <thead class="text-left text-sky-900 bg-sky-50 border-b border-sky-100">
                            <tr>
                                <th scope="col" class="py-2 pr-4">ID</th>
                                <th scope="col" class="py-2 pr-4">Excursión</th>
                                <th scope="col" class="py-2 pr-4">Día</th>
                                <th scope="col" class="py-2 pr-4">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-50">
                            @forelse ($createdMeetings as $meeting)
                                <tr class="hover:bg-sky-50/40">
                                    <td class="py-2 pr-4">#{{ $meeting->id }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->trek?->name ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->day_formatted }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->hour }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-gray-500">No tiene encuentros como guía principal.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-emerald-900 mb-3">Encuentros asociados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <caption class="sr-only">Tabla de datos</caption>
                        <thead class="text-left text-emerald-900 bg-emerald-50 border-b border-emerald-100">
                            <tr>
                                <th scope="col" class="py-2 pr-4">ID</th>
                                <th scope="col" class="py-2 pr-4">Excursión</th>
                                <th scope="col" class="py-2 pr-4">Día</th>
                                <th scope="col" class="py-2 pr-4">Hora</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50">
                            @forelse ($user->meetings as $meeting)
                                <tr class="hover:bg-emerald-50/40">
                                    <td class="py-2 pr-4">#{{ $meeting->id }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->trek?->name ?? '-' }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->day_formatted }}</td>
                                    <td class="py-2 pr-4">{{ $meeting->hour }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-4 text-gray-500">No tiene encuentros asociados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/90 border border-amber-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-amber-900 mb-3">Comentarios</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <caption class="sr-only">Tabla de datos</caption>
                        <thead class="text-left text-amber-900 bg-amber-50 border-b border-amber-100">
                            <tr>
                                <th scope="col" class="py-2 pr-4">ID</th>
                                <th scope="col" class="py-2 pr-4">Encuentro</th>
                                <th scope="col" class="py-2 pr-4">Puntuación</th>
                                <th scope="col" class="py-2 pr-4">Estado</th>
                                <th scope="col" class="py-2 pr-4">Texto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-amber-50">
                            @forelse ($user->comments as $comment)
                                <tr class="hover:bg-amber-50/40">
                                    <td class="py-2 pr-4">#{{ $comment->id }}</td>
                                    <td class="py-2 pr-4">
                                        {{ $comment->meeting?->trek?->name ?? '-' }} (#{{ $comment->meeting_id }})
                                    </td>
                                    <td class="py-2 pr-4">{{ $comment->score }}</td>
                                    <td class="py-2 pr-4">{{ $comment->status === 'y' ? 'Aprobado' : 'Pendiente' }}</td>
                                    <td class="py-2 pr-4">{{ $comment->comment }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-gray-500">No tiene comentarios.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
