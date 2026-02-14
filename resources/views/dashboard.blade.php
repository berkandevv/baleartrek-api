<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-amber-50 via-red-50 to-orange-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 overflow-hidden shadow-sm sm:rounded-2xl border-4 border-red-500">
                <div class="p-8 text-center">
                    <p class="text-xl sm:text-3xl font-extrabold leading-tight animate-pulse">
                        <span class="text-red-700">Ahora tienes TODO el poder</span>
                        <span class="text-red-600">...</span>
                        <span class="text-red-700"> y recuerda:</span>
                        <br>
                        <span class="text-red-700">un gran poder</span>
                        <span class="text-red-600"> conlleva una gran responsabilidad</span>
                        <span class="text-red-700">.</span>
                        <br>
                        <span class="text-amber-700">(Úsalo sabiamente o no rompas producción)</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
