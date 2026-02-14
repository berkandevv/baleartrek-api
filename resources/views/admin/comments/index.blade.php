<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comentarios
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.comments.index', ['status' => 'all', 'trek_id' => $trekId]) }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ $status === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">
                                Todos
                            </a>
                            <a href="{{ route('admin.comments.index', ['status' => 'approved', 'trek_id' => $trekId]) }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ $status === 'approved' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">
                                Aprobados
                            </a>
                            <a href="{{ route('admin.comments.index', ['status' => 'pending', 'trek_id' => $trekId]) }}"
                                class="px-3 py-2 rounded-md text-sm font-medium {{ $status === 'pending' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700' }}">
                                Pendientes
                            </a>
                        </div>

                        <form method="GET" action="{{ route('admin.comments.index') }}"
                            class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-end">
                            <input type="hidden" name="status" value="{{ $status }}">
                            <div class="w-full sm:w-72">
                                <x-input-label for="trek_id" value="Ruta" />
                                <select id="trek_id" name="trek_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-blue-600 focus:ring-blue-500 rounded-md shadow-sm">
                                    <option value="all" @selected($trekId === 'all')>Todas</option>
                                    @foreach ($treks as $trek)
                                        <option value="{{ $trek->id }}" @selected((string) $trekId === (string) $trek->id)>
                                            {{ $trek->regnumber }} - {{ $trek->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2 sm:pb-0.5">
                                <x-primary-button type="submit">Filtrar</x-primary-button>
                                @if ($trekId !== 'all')
                                    <a href="{{ route('admin.comments.index', ['status' => $status]) }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                        Limpiar
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-600 border-b">
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
                                            <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                                                Ver
                                            </a>
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
                {{ $comments->links('admin.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>
