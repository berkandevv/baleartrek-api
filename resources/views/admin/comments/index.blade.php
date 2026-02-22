<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-cyan-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <x-flash-status class="mb-4" />

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <form method="GET" action="{{ route('admin.comments.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end sm:flex-1">
                            <div class="w-full sm:max-w-xs">
                                <x-input-label for="status" value="Estado" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="all" @selected($status === 'all')>Todos</option>
                                    <option value="approved" @selected($status === 'approved')>Aprobados</option>
                                    <option value="pending" @selected($status === 'pending')>Pendientes</option>
                                </select>
                            </div>
                            <div class="w-full sm:max-w-md">
                                <x-input-label for="trek_id" value="Ruta" />
                                <select id="trek_id" name="trek_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="all" @selected($trekId === 'all')>Todas</option>
                                    @foreach ($treks as $trek)
                                        <option value="{{ $trek->id }}" @selected((string) $trekId === (string) $trek->id)>
                                            {{ $trek->regnumber }} - {{ $trek->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <x-primary-button type="submit">
                                    Buscar
                                </x-primary-button>
                                @if ($status !== 'all' || $trekId !== 'all')
                                    <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                        Limpiar
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-sky-900 bg-sky-50 border-b border-sky-100">
                                <tr>
                                    <th class="py-2 pr-4">ID</th>
                                    <th class="py-2 pr-4">Usuario</th>
                                    <th class="py-2 pr-4">Ruta</th>
                                    <th class="py-2 pr-4">Encuentro</th>
                                    <th class="py-2 pr-4">Puntuación</th>
                                    <th class="py-2 pr-4">Estado</th>
                                    <th class="py-2 pr-4">Imágenes</th>
                                    <th class="py-2 pr-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($comments as $comment)
                                    <tr class="border-b">
                                        <td class="py-2 pr-4">{{ $comment->id }}</td>
                                        <td class="py-2 pr-4">
                                            {{ $comment->user?->name }} {{ $comment->user?->lastname }}
                                        </td>
                                        <td class="py-2 pr-4">
                                            {{ $comment->meeting?->trek?->name ?? '-' }}
                                        </td>
                                        <td class="py-2 pr-4">
                                            @if ($comment->meeting)
                                                <div>#{{ $comment->meeting->id }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $comment->meeting->day_formatted ?: '-' }}
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">{{ $comment->score }}</td>
                                        <td class="py-2 pr-4">
                                            @if ($comment->status === 'y')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-green-700 bg-green-100 rounded-full">
                                                    Aprobado
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-red-700 bg-red-100 rounded-full">
                                                    Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-2 pr-4">{{ $comment->images_count }}</td>
                                        <td class="py-2 pr-4 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('admin.comments.show', $comment->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-green-700 rounded-md hover:bg-green-600">
                                                    Ver
                                                </a>
                                                <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-6 text-center text-gray-500">
                                            No hay comentarios para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-2 flex justify-end">
                {{ $comments->links('admin.partials.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
