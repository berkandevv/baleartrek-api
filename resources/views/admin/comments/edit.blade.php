<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comentario #{{ $comment->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900 space-y-6">
                    <x-flash-status />
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <div class="text-xs uppercase text-gray-500">Usuario</div>
                            <div class="font-medium">
                                {{ $comment->user?->name }} {{ $comment->user?->lastname }}
                            </div>
                            <div class="text-sm text-gray-600">{{ $comment->user?->email }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-gray-500">Ruta</div>
                            <div class="font-medium">{{ $comment->meeting?->trek?->name ?? '-' }}</div>
                        </div>
                        <div class="sm:col-span-2">
                            <div class="text-xs uppercase text-gray-500">Encuentro</div>
                            @if ($comment->meeting)
                                <div class="mt-1 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                    <div class="flex items-center justify-between gap-3 py-1">
                                        <span class="text-sm text-gray-600">ID</span>
                                        <span class="font-medium text-gray-900">#{{ $comment->meeting->id }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 py-1 border-t border-slate-200">
                                        <span class="text-sm text-gray-600">Día del encuentro</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $comment->meeting->day_formatted ?: '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 py-1 border-t border-slate-200">
                                        <span class="text-sm text-gray-600">Inicio inscripción</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $comment->meeting->app_date_ini_formatted ?: '-' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3 py-1 border-t border-slate-200">
                                        <span class="text-sm text-gray-600">Fin inscripción</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $comment->meeting->app_date_end_formatted ?: '-' }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="font-medium">-</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-xs uppercase text-gray-500">Puntuación</div>
                            <div class="font-medium">{{ $comment->score }}</div>
                        </div>
                        <div>
                            <div class="text-xs uppercase text-gray-500">Estado</div>
                            <div class="font-medium">{{ $comment->status === 'y' ? 'Aprobado' : 'Pendiente' }}</div>
                        </div>
                    </div>

                    <div class="border-t pt-6">
                        <div class="text-xs uppercase text-gray-500">Comentario</div>
                        <p class="mt-2 text-gray-900">{{ $comment->comment }}</p>
                    </div>

                    <form method="POST" action="{{ route('admin.comments.update', $comment->id) }}" class="border-t pt-6">
                        @csrf
                        @method('PATCH')

                        <x-input-label for="status" value="Estado" />
                        <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="y" @selected(old('status', $comment->status) === 'y')>Aprobar</option>
                            <option value="n" @selected(old('status', $comment->status) === 'n')>Pendiente</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />

                        <div class="mt-4 flex items-center gap-3">
                            <x-primary-button type="submit">
                                Guardar
                            </x-primary-button>
                            <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Volver a la lista
                            </a>
                        </div>
                    </form>

                    <div class="border-t pt-6">
                        <div class="text-xs uppercase text-gray-500">Imágenes</div>
                        @if ($comment->images->isEmpty())
                            <p class="mt-2 text-sm text-gray-600">Sin imágenes.</p>
                        @else
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach ($comment->images as $image)
                                    <div class="border rounded-md p-3">
                                        <div class="text-sm text-gray-600 break-all">{{ $image->url }}</div>
                                        <form method="POST" action="{{ route('admin.comments.images.destroy', [$comment->id, $image->id]) }}" class="mt-3" onsubmit="return confirm('¿Seguro que quieres eliminar esta imagen?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">
                                                Eliminar imagen
                                            </x-danger-button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
