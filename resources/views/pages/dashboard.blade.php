<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-amber-50 via-red-50 to-orange-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 overflow-hidden shadow-sm sm:rounded-2xl border-4 border-red-500">
                <div class="p-8 text-center">
                    @if (Auth::user()?->isAdmin())
                        <p class="text-xl sm:text-3xl font-extrabold leading-tight">
                            <span class="text-slate-800">Panel de administración de BalearTrek</span>
                            <br>
                            <span class="text-slate-600 text-lg sm:text-2xl">Gestiona usuarios, excursiones, encuentros y moderación de contenidos.</span>
                        </p>
                    @else
                        <p class="text-xl sm:text-3xl font-extrabold leading-tight">
                            <span class="text-slate-800">Bienvenido a tu panel de BalearTrek</span>
                            <br>
                            <span class="text-slate-600 text-lg sm:text-2xl">Consulta tus datos y gestiona tu actividad.</span>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
