<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Security headers para development -->
    @if(app()->environment('local'))
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; img-src 'self' data: blob: *; font-src 'self' data: https://fonts.bunny.net; connect-src 'self' ws: wss:; form-action 'self';">
    <meta name="referrer" content="no-referrer-when-downgrade">
    @else
    <!-- Headers seguros para Railway production -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; img-src 'self' data: blob: *; font-src 'self' https://fonts.bunny.net; connect-src 'self'; form-action 'self';">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    @endif
    
    <!-- Dark mode detection script (runs before page render) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <title>{{ config('app.name', 'Runa Maki') }}</title>

    <!-- Fonts: usando fuentes del sistema para evitar problemas de CSP -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Alpine.js x-cloak directive */
        [x-cloak] { display: none !important; }
        
        /* Fallback to ensure banner hides on small screens if Tailwind isn't applied immediately */
        @media (max-width: 767px) {
            .desktop-banner { display: none !important; }
            .mobile-banner-icon { display: block !important; }
        }
        @media (min-width: 768px) {
            .mobile-banner-icon { display: none !important; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-100 dark:bg-gray-900 transition-colors duration-200">
    <div class="min-h-screen flex">
        @auth
    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-slate-300 dark:border-gray-700 transform -translate-x-full lg:translate-x-0 transition-all duration-300 shadow-lg lg:shadow-xl">
            <!-- Logo & Brand -->
            <div class="h-16 flex items-center px-6 border-b border-slate-300 dark:border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight text-gray-800 dark:text-white">
                    <span class="text-indigo-600 dark:text-indigo-400">Runa</span> <span class="text-gray-600 dark:text-gray-300">Maki</span>
                </a>
            </div>

            <!-- User Info -->
            <div class="p-4 border-b border-slate-300 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-semibold ring-2 ring-slate-300 dark:ring-gray-600 shadow-md">
                        {{ Auth::user()->iniciales }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-0.5 bg-slate-200 dark:bg-gray-700 text-slate-800 dark:text-gray-300 rounded font-medium">{{ Auth::user()->nivel }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">• {{ Auth::user()->nivel_emoji }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs text-gray-700 dark:text-gray-400">
                    <span>Runas</span>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->puntos_runa }}</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('habilidades.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('habilidades.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Habilidades
                </a>
                
                <!-- Submenú de Habilidades -->
                @if(request()->routeIs('habilidades.*'))
                    <div class="ml-6 space-y-1">
                        <a href="{{ route('habilidades.buscar') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-md text-xs font-medium transition text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            🔍 Búsqueda inteligente
                        </a>
                        <a href="{{ route('habilidades.busqueda-avanzada') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-md text-xs font-medium transition text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            ⚙️ Búsqueda avanzada
                        </a>
                        @auth
                            <a href="{{ route('habilidades.create') }}" class="flex items-center gap-3 px-3 py-1.5 rounded-md text-xs font-medium transition text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                                ✨ Crear habilidad
                            </a>
                        @endauth
                    </div>
                @endif
                <a href="{{ route('trueques.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('trueques.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    Mis Trueques
                </a>
                <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('chat.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.965 8.965 0 01-4.57-1.22L3 21l2.22-5.43A8.965 8.965 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                    </svg>
                    Chat
                </a>
                <a href="{{ route('perfil.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('perfil.*') ? 'bg-indigo-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Mi Perfil
                </a>

                @if(Auth::user()->isAdmin())
                    <!-- Separador para Admin -->
                    <div class="border-t border-gray-300 dark:border-gray-600 my-2"></div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.*') ? 'bg-gradient-to-r from-purple-600 to-purple-700 text-white' : 'text-purple-700 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path>
                        </svg>
                        Panel Admin
                    </a>
                @endif
            </nav>

            <!-- Actions -->
            <div class="p-4 border-t border-slate-300 dark:border-gray-700 space-y-2">
                <!-- Dark Mode Toggle -->
                <button id="dark-mode-toggle" class="flex items-center justify-center gap-2 w-full px-4 py-2 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg id="dark-mode-icon-sun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="dark-mode-icon-moon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <span id="dark-mode-text">Modo Oscuro</span>
                </button>
                
                <a href="{{ route('habilidades.create') }}" class="block w-full px-4 py-2 border border-indigo-200 dark:border-indigo-700 text-indigo-700 dark:text-indigo-400 text-sm font-medium rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition text-center">
                    + Nueva Habilidad
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-gray-700 dark:text-gray-300 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md transition text-center">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay (visible when sidebar is open on mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity lg:hidden"></div>
        @endauth

        <!-- Main Content Area -->
        <div class="flex-1 {{ auth()->check() ? 'lg:ml-64' : '' }}">
            @guest
            <!-- Top Bar for Guests -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 sticky top-0 z-30 shadow-md">"
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('habilidades.index') }}" class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                                Runa Maki
                            </a>
                            <a href="{{ route('habilidades.index') }}" class="ml-8 text-sm text-gray-700 hover:text-gray-900">Habilidades</a>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">Registrarse</a>
                        </div>
                    </div>
                </div>
            </nav>
            @endguest

            @auth
            <!-- Motivational Banner Component -->
            <x-motivational-banner />

            <!-- Top utility bar: compact search + quick stats -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-300 dark:border-gray-700 shadow-sm">"
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @auth
                    @php
                        $habilidadesCount = Auth::user()->habilidades()->count();
                        $pendientesCount = Auth::user()->truequesOfrecidos()->where('estado','pendiente')->count() + Auth::user()->truequesRecibidos()->where('estado','pendiente')->count();
                        $mensajesNoLeidos = Auth::user()->mensajes()->where('leido', false)->count();
                    @endphp
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-4">
                            <!-- Mobile menu button (visible only on mobile) -->
                            <button id="sidebar-toggle" class="lg:hidden p-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            <form action="{{ route('habilidades.buscar') }}" method="GET" class="hidden md:flex items-center gap-2">
                                <div class="relative">
                                    <input name="q" type="text" placeholder="🔍 Búsqueda inteligente..." value="{{ request('q') }}" 
                                           class="h-9 rounded-md border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm px-3 pr-10 text-sm w-72 focus:ring-0 focus:border-indigo-300 dark:focus:border-indigo-500" 
                                           title="Busca con palabras clave, sinónimos o términos relacionados" />
                                    <button type="button" 
                                            onclick="window.location.href='{{ route('habilidades.busqueda-avanzada') }}'"
                                            class="absolute right-1 top-1 h-7 px-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition text-xs"
                                            title="Búsqueda avanzada">
                                        ⚙️
                                    </button>
                                </div>
                                <button type="submit" class="h-9 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm transition font-medium">Buscar</button>
                            </form>

                            <div class="hidden sm:flex items-center gap-2">
                                <div class="text-xs text-slate-700 dark:text-gray-400 px-3 py-1 bg-slate-200 dark:bg-gray-700 rounded-md border border-slate-300 dark:border-gray-600">Habilidades <span class="font-semibold text-slate-900 dark:text-white ml-2">{{ $habilidadesCount }}</span></div>
                                <div class="text-xs text-slate-700 dark:text-gray-400 px-3 py-1 bg-slate-200 dark:bg-gray-700 rounded-md border border-slate-300 dark:border-gray-600">Pendientes <span class="font-semibold text-slate-900 dark:text-white ml-2">{{ $pendientesCount }}</span></div>
                                <div class="text-xs text-slate-700 dark:text-gray-400 px-3 py-1 bg-slate-200 dark:bg-gray-700 rounded-md border border-slate-300 dark:border-gray-600">Mensajes <span class="font-semibold text-slate-900 dark:text-white ml-2">{{ $mensajesNoLeidos }}</span></div>
                            </div>
                        </div>

                        <!-- Centered brand/title -->
                        <div class="hidden md:flex flex-1 justify-center">
                            <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-slate-900 dark:text-white">Runa Maki</a>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('trueques.index', ['estado' => 'pendiente']) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Ver pendientes</a>
                            <a href="{{ route('trueques.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Mensajes</a>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
            @endauth

            <!-- Page Content -->
            <main class="min-h-screen bg-slate-50 dark:bg-gray-900">
                <div class="py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <!-- Flash Messages -->
                        @if (session('success'))
                            <div class="alert-success mb-6" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert-error mb-6" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer - Full Width -->
    <footer class="bg-gradient-to-r from-gray-800 via-gray-700 to-gray-800 dark:from-black dark:via-gray-950 dark:to-black text-gray-900 dark:text-white border-t border-gray-700 dark:border-gray-800">
        <div class="{{ auth()->check() ? 'lg:ml-64' : '' }}">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Main Footer Content -->
                    <div class="py-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- About Section -->
                        <div class="space-y-4">
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                                Runa Maki
                            </h3>
                            <p class="text-gray-200 dark:text-gray-300 text-sm leading-relaxed">
                                Plataforma innovadora de intercambio de habilidades. Aprende, enseña y crece en comunidad.
                            </p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-300 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition transform hover:scale-110">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                                <a href="#" class="text-gray-300 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition transform hover:scale-110">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </a>
                                <a href="https://github.com/AbsaCG/runamaki3.0" target="_blank" class="text-gray-300 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition transform hover:scale-110">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-indigo-500 dark:text-indigo-300">Enlaces Rápidos</h4>
                            <ul class="space-y-2">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('habilidades.index') }}" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Explorar Habilidades
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('trueques.index') }}" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Mis Trueques
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('perfil.index') }}" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Mi Perfil
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Resources -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-indigo-500 dark:text-indigo-300">Recursos</h4>
                            <ul class="space-y-2">
                                <li>
                                    <a href="#" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Cómo Funciona
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Guía de Usuario
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Preguntas Frecuentes
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="text-gray-200 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-white transition text-sm flex items-center group">
                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        Contacto
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Stats & Info -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-indigo-500 dark:text-indigo-300">Estadísticas</h4>
                            <div class="space-y-3">
                                <div class="bg-white/5 dark:bg-white/5 backdrop-blur-sm rounded-lg p-3 border border-gray-600 dark:border-white/10 hover:border-indigo-400/50 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-300 dark:text-gray-400 text-xs">Usuarios Activos</span>
                                        <span class="text-2xl font-bold text-indigo-400">{{ \App\Models\User::count() }}</span>
                                    </div>
                                </div>
                                <div class="bg-white/5 dark:bg-white/5 backdrop-blur-sm rounded-lg p-3 border border-gray-600 dark:border-white/10 hover:border-purple-400/50 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-300 dark:text-gray-400 text-xs">Habilidades</span>
                                        <span class="text-2xl font-bold text-purple-400">{{ \App\Models\Habilidad::count() }}</span>
                                    </div>
                                </div>
                                <div class="bg-white/5 dark:bg-white/5 backdrop-blur-sm rounded-lg p-3 border border-gray-600 dark:border-white/10 hover:border-pink-400/50 transition">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-300 dark:text-gray-400 text-xs">Trueques</span>
                                        <span class="text-2xl font-bold text-pink-400">{{ \App\Models\Trueque::count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Footer -->
                    <div class="border-t border-gray-600 dark:border-white/10 py-6">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            <div class="text-gray-200 dark:text-gray-400 text-sm text-center md:text-left">
                                <p>© {{ date('Y') }} <span class="text-indigo-500 dark:text-indigo-400 font-semibold">Runa Maki</span>. Todos los derechos reservados.</p>
                                <p class="text-xs mt-1">Desarrollado con ❤️ para la comunidad universitaria</p>
                            </div>
                            <div class="flex items-center space-x-6 text-sm">
                                <a href="#" class="text-gray-200 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Términos</a>
                                <span class="text-gray-500 dark:text-gray-600">•</span>
                                <a href="#" class="text-gray-200 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Privacidad</a>
                                <span class="text-gray-500 dark:text-gray-600">•</span>
                                <a href="#" class="text-gray-200 dark:text-gray-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition">Ayuda</a>
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-gray-300 dark:text-gray-500">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                                <span>Hecho con <span class="text-red-500">Laravel</span> & <span class="text-blue-500">Tailwind CSS</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    <!-- Sidebar Toggle Script -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                if (sidebar && sidebarOverlay) {
                    // Toggle sidebar visibility
                    const isHidden = sidebar.classList.contains('-translate-x-full');
                    
                    if (isHidden) {
                        // Show sidebar
                        sidebar.classList.remove('-translate-x-full');
                        sidebarOverlay.classList.remove('hidden');
                    } else {
                        // Hide sidebar
                        sidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                    }
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    // Close sidebar when clicking overlay
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }

            // Close sidebar on window resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024 && sidebar && sidebarOverlay) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                }
            });

            // Close sidebar when clicking on navigation links on mobile
            if (window.innerWidth < 1024) {
                const navLinks = sidebar.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar.classList.add('-translate-x-full');
                        sidebarOverlay.classList.add('hidden');
                    });
                });
            }

            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const darkModeIconSun = document.getElementById('dark-mode-icon-sun');
            const darkModeIconMoon = document.getElementById('dark-mode-icon-moon');
            const darkModeText = document.getElementById('dark-mode-text');
            
            // Update UI based on current theme (already set by initial script)
            function updateDarkModeUI() {
                const isDark = document.documentElement.classList.contains('dark');
                
                if (isDark) {
                    darkModeIconSun.classList.remove('hidden');
                    darkModeIconMoon.classList.add('hidden');
                    darkModeText.textContent = 'Modo Claro';
                } else {
                    darkModeIconSun.classList.add('hidden');
                    darkModeIconMoon.classList.remove('hidden');
                    darkModeText.textContent = 'Modo Oscuro';
                }
            }
            
            // Initialize UI
            updateDarkModeUI();
            
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    const isDark = document.documentElement.classList.contains('dark');
                    
                    if (isDark) {
                        // Switch to light mode
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        // Switch to dark mode
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                    
                    // Update UI
                    updateDarkModeUI();
                });
            }
        });
    </script>
    @endauth
</body>
</html>