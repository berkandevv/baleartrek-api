<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 border border-slate-200 shadow-sm sm:rounded-2xl">
                <div class="p-6 text-slate-900">
                    <form method="POST" action="{{ route('admin.municipalities.store') }}" class="space-y-6">
                        @csrf

                        @include('admin.municipalities.form')

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">
                                Crear municipio
                            </x-primary-button>
                            <a href="{{ route('admin.municipalities.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold uppercase tracking-widest text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                                Volver a la lista
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
