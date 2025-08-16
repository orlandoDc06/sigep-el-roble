<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>El Roble - {{ $titulo ?? View::getSection('titulo') ?? 'SIGEP' }}</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100">
    <header class="p-5 bg-white shadow">
        <div class="container mx-auto flex justify-between items-center">
               <a href="{{ auth()->check() ? route('dashboard.redirect') : route('login') }}" class="flex items-center space-x-3">
                <img src="{{ asset('images/logo roble.png') }}" alt="Logo El Roble" class="h-10 w-auto">
                <span class="text-3xl font-black text-green-700">Ferretería El Roble</span>
            </a>
            @auth
                <nav class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        Hola,
                        <a  class="font-semibold text-gray-700 hover:underline">
                            {{ auth()->user()->employee->first_name ?? auth()->user()->email }}
                        </a>
                    </span>

                    @role('admin')
                        <details class="relative">
                            <summary class="cursor-pointer text-sm font-semibold text-gray-700 hover:underline">
                                Administración
                            </summary>
                            <div class="absolute right-0 mt-2 w-56 bg-white border rounded shadow-md z-10">
                                <a 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Gestión de Sucursales
                                </a>
                                <a 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Tipos de Contrato
                                </a>
                                <a 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Turnos
                                </a>
                                <a 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Empleados
                                </a>
                            </div>
                        </details>
                    @endrole
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-600 hover:underline font-semibold uppercase cursor-pointer">
                            Cerrar sesión
                        </button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="flex gap-2 items-center">
                    <a href="{{ route('login') }}" class="font-bold uppercase text-gray-600 text-sm">Login</a>
                </nav>
            @endguest
        </div>
    </header>
    <main class="container mx-auto mt-10">
        <h2 class="font-black text-center text-3xl mb-10">
            {{ $titulo ?? View::getSection('titulo') ?? '' }}
        </h2>
        @hasSection('contenido')
            @yield('contenido')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>
    <footer class="text-center p-5 text-gray-500 font-bold uppercase mt-10">
        Ferretería El Roble - Todos los derechos reservados {{ now()->year }}
    </footer>
    @livewireScripts
</body>
</html>