<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900">Detalle de usuario #{{ $user->id }}</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div><dt class="text-gray-500">Nombre</dt><dd class="font-medium">{{ $user->name }}</dd></div>
                    <div><dt class="text-gray-500">Apellidos</dt><dd class="font-medium">{{ $user->lastname }}</dd></div>
                    <div><dt class="text-gray-500">DNI</dt><dd class="font-medium">{{ $user->dni }}</dd></div>
                    <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $user->email }}</dd></div>
                    <div><dt class="text-gray-500">Teléfono</dt><dd class="font-medium">{{ $user->phone }}</dd></div>
                    <div><dt class="text-gray-500">Rol</dt><dd class="font-medium">{{ $user->role?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Estado</dt><dd class="font-medium">{{ $user->status === 'y' ? 'Alta' : 'Baja' }}</dd></div>
                    <div><dt class="text-gray-500">Email verificado</dt><dd class="font-medium">{{ $user->email_verified_at ? $user->email_verified_at->format('d-m-Y H:i') : 'No' }}</dd></div>
                    <div><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $user->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $user->updated_at?->format('d-m-Y H:i') }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Encuentros como guía principal</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Excursión</th>
                                <th class="py-2 pr-4">Día</th>
                                <th class="py-2 pr-4">Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($createdMeetings as $meeting)
                                <tr class="border-b">
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

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Encuentros asociados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Excursión</th>
                                <th class="py-2 pr-4">Día</th>
                                <th class="py-2 pr-4">Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->meetings as $meeting)
                                <tr class="border-b">
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

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Comentarios</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-600 border-b">
                            <tr>
                                <th class="py-2 pr-4">ID</th>
                                <th class="py-2 pr-4">Encuentro</th>
                                <th class="py-2 pr-4">Puntuación</th>
                                <th class="py-2 pr-4">Estado</th>
                                <th class="py-2 pr-4">Texto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($user->comments as $comment)
                                <tr class="border-b">
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
