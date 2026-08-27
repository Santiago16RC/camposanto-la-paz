<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-stone-50 text-stone-800 dark:bg-stone-950 dark:text-stone-200">
        <div class="min-h-screen flex flex-col">
            <header class="border-b border-stone-200 dark:border-stone-800">
                <div class="max-w-6xl mx-auto px-6 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <x-application-logo class="h-8 w-8 fill-current text-emerald-800 dark:text-emerald-500" />
                        <span class="font-semibold text-lg">{{ config('app.name') }}</span>
                    </div>

                    @if (Route::has('login'))
                        <livewire:welcome.navigation />
                    @endif
                </div>
            </header>

            <main class="flex-1">
                <section class="max-w-6xl mx-auto px-6 py-20 text-center">
                    <h1 class="text-3xl sm:text-5xl font-semibold tracking-tight text-stone-900 dark:text-white">
                        {{ config('app.name') }}
                    </h1>
                    <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg text-stone-600 dark:text-stone-400">
                        Un espacio de paz y memoria. Accede al portal para consultar información
                        de servicios, lotes y trámites administrativos del camposanto.
                    </p>

                    <div class="mt-8 flex items-center justify-center gap-4">
                        <a
                            href="{{ route('login') }}"
                            wire:navigate
                            class="rounded-md bg-emerald-800 px-6 py-3 text-white font-medium shadow-sm transition hover:bg-emerald-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-700 focus-visible:ring-offset-2"
                        >
                            Iniciar sesión
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                wire:navigate
                                class="rounded-md px-6 py-3 font-medium text-stone-700 ring-1 ring-stone-300 transition hover:bg-stone-100 dark:text-stone-300 dark:ring-stone-700 dark:hover:bg-stone-900"
                            >
                                Crear cuenta
                            </a>
                        @endif
                    </div>
                </section>

                <section class="max-w-6xl mx-auto px-6 pb-20 grid gap-6 sm:grid-cols-3">
                    <div class="rounded-lg bg-white dark:bg-stone-900 p-6 ring-1 ring-stone-200 dark:ring-stone-800">
                        <h2 class="font-semibold text-stone-900 dark:text-white">Consulta de lotes</h2>
                        <p class="mt-2 text-sm text-stone-600 dark:text-stone-400">Revisa la ubicación y estado de los lotes registrados a tu nombre.</p>
                    </div>
                    <div class="rounded-lg bg-white dark:bg-stone-900 p-6 ring-1 ring-stone-200 dark:ring-stone-800">
                        <h2 class="font-semibold text-stone-900 dark:text-white">Trámites administrativos</h2>
                        <p class="mt-2 text-sm text-stone-600 dark:text-stone-400">Gestiona solicitudes y da seguimiento a tus trámites en línea.</p>
                    </div>
                    <div class="rounded-lg bg-white dark:bg-stone-900 p-6 ring-1 ring-stone-200 dark:ring-stone-800">
                        <h2 class="font-semibold text-stone-900 dark:text-white">Atención a familiares</h2>
                        <p class="mt-2 text-sm text-stone-600 dark:text-stone-400">Contacta al equipo administrativo para cualquier duda o solicitud.</p>
                    </div>
                </section>
            </main>

            <footer class="border-t border-stone-200 dark:border-stone-800 py-8 text-center text-sm text-stone-500 dark:text-stone-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
            </footer>
        </div>
    </body>
</html>
