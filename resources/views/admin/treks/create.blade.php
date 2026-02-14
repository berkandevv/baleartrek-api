<x-app-layout>
    <div class="py-6 bg-gradient-to-br from-cyan-50 via-emerald-50 to-white">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center bg-white/90 border border-cyan-100 shadow-sm sm:rounded-2xl px-5 py-4">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold uppercase tracking-widest text-cyan-700 bg-cyan-100 rounded-full">Excursión</span>
                    <h2 class="mt-2 font-semibold text-xl text-gray-800 leading-tight">Crear nueva excursión</h2>
                </div>
                <a href="{{ route('admin.treks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    Volver
                </a>
            </div>

            <div class="bg-white/90 border border-cyan-100 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <form method="POST" action="{{ route('admin.treks.store') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        @include('admin.treks.form')

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">
                                Crear excursión
                            </x-primary-button>
                            <a href="{{ route('admin.treks.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Volver a la lista
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
