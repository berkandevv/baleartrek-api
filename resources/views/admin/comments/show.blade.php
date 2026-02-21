<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-sky-50 via-cyan-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">
            <div class="flex justify-between items-center bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-sky-700 bg-sky-100 rounded-full">Comentario</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-sky-100 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div class="rounded-lg border border-sky-100 bg-sky-50/50 p-3"><dt class="text-gray-500">Usuario</dt><dd class="font-medium">{{ $comment->user?->name }} {{ $comment->user?->lastname }}</dd></div>
                    <div class="rounded-lg border border-sky-100 bg-sky-50/50 p-3"><dt class="text-gray-500">Rol usuario</dt><dd class="font-medium">{{ $comment->user?->role?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/50 p-3"><dt class="text-gray-500">Encuentro</dt><dd class="font-medium">#{{ $comment->meeting?->id ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/50 p-3"><dt class="text-gray-500">Excursión</dt><dd class="font-medium">{{ $comment->meeting?->trek?->name ?? '-' }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/50 p-3"><dt class="text-gray-500">Guía principal</dt><dd class="font-medium">{{ $comment->meeting?->user?->name }} {{ $comment->meeting?->user?->lastname }}</dd></div>
                    <div class="rounded-lg border border-cyan-100 bg-cyan-50/50 p-3"><dt class="text-gray-500">Puntuación</dt><dd class="font-medium">{{ $comment->score }}</dd></div>
                    <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-3"><dt class="text-gray-500">Estado</dt><dd class="font-medium">{{ $comment->status === 'y' ? 'Aprobado' : 'Pendiente' }}</dd></div>
                    <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-3"><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $comment->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="rounded-lg border border-blue-100 bg-blue-50/50 p-3"><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $comment->updated_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="md:col-span-2 rounded-lg border border-slate-200 bg-slate-50 p-3"><dt class="text-gray-500">Texto</dt><dd class="font-medium whitespace-pre-wrap">{{ $comment->comment }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-cyan-100 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-cyan-900 mb-3">Imágenes</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($comment->images as $image)
                            <div class="border border-cyan-100 bg-cyan-50/30 rounded-lg p-3">
                                @if ($image->display_url)
                                    <img src="{{ $image->display_url }}" alt="Imagen del comentario {{ $comment->id }}" class="w-full h-48 object-cover rounded-md">
                                @else
                                    <div class="w-full h-48 rounded-md bg-slate-100 text-slate-500 text-sm flex items-center justify-center">
                                        Imagen no disponible
                                    </div>
                                @endif
                                <p class="mt-2 text-xs text-gray-500 break-all">{{ $image->url }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Sin imágenes asociadas.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
