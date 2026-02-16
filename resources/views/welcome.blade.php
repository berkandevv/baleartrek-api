<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'BalearTrek') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-sky-50 via-cyan-50 to-white text-slate-900 antialiased">
        <main class="mx-auto flex min-h-screen max-w-6xl items-center px-6 py-16">
            <section class="w-full rounded-3xl border border-sky-100 bg-white/90 p-8 shadow-sm sm:p-12">
                <div class="mb-8 flex items-center justify-between gap-4">
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        {{ config('app.name', 'BalearTrek') }}
                    </h1>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-2">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-slate-800">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-700 hover:bg-slate-50">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-slate-800">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>

                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Plataforma para la gestion de excursiones, encuentros y comunidad.
                    Esta vista de entrada se ha simplificado para facilitar mantenimiento y evitar
                    bloques de HTML/CSS generados demasiado extensos.
                </p>
            </section>
        </main>
    </body>
</html>
