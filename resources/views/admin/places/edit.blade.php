<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-emerald-50 via-lime-50 to-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-emerald-700 bg-emerald-100 rounded-full">Lugar</span>
                    <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">Editar lugar</h2>
                </div>
            </div>

            <div class="bg-white/90 border border-emerald-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <form method="POST" action="{{ route('admin.places.update', $place->id) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        @include('admin.places.form', [
                            'place' => $place,
                        ])

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">
                                Guardar cambios
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
