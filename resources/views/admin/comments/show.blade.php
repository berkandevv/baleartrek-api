<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-slate-900">Detalle de comentario #{{ $comment->id }}</h2>
                <div class="flex gap-2">
                    <a href="{{ route('admin.comments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                        Volver
                    </a>
                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white bg-blue-900 rounded-md hover:bg-blue-800">
                        Editar
                    </a>
                </div>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-800">
                    <div><dt class="text-gray-500">Usuario</dt><dd class="font-medium">{{ $comment->user?->name }} {{ $comment->user?->lastname }}</dd></div>
                    <div><dt class="text-gray-500">Rol usuario</dt><dd class="font-medium">{{ $comment->user?->role?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Encuentro</dt><dd class="font-medium">#{{ $comment->meeting?->id ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Excursión</dt><dd class="font-medium">{{ $comment->meeting?->trek?->name ?? '-' }}</dd></div>
                    <div><dt class="text-gray-500">Guía principal</dt><dd class="font-medium">{{ $comment->meeting?->user?->name }} {{ $comment->meeting?->user?->lastname }}</dd></div>
                    <div><dt class="text-gray-500">Puntuación</dt><dd class="font-medium">{{ $comment->score }}</dd></div>
                    <div><dt class="text-gray-500">Estado</dt><dd class="font-medium">{{ $comment->status === 'y' ? 'Aprobado' : 'Pendiente' }}</dd></div>
                    <div><dt class="text-gray-500">Creado</dt><dd class="font-medium">{{ $comment->created_at?->format('d-m-Y H:i') }}</dd></div>
                    <div><dt class="text-gray-500">Actualizado</dt><dd class="font-medium">{{ $comment->updated_at?->format('d-m-Y H:i') }}</dd></div>
                    <div class="md:col-span-2"><dt class="text-gray-500">Texto</dt><dd class="font-medium whitespace-pre-wrap">{{ $comment->comment }}</dd></div>
                </dl>
            </div>

            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-3">Imágenes</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($comment->images as $image)
                        <div class="border border-slate-200 rounded-lg p-3">
                            <img src="{{ $image->url }}" alt="Imagen del comentario {{ $comment->id }}" class="w-full h-48 object-cover rounded-md">
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
