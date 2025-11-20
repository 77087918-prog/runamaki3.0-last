<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Runa Maki') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex relative">
        <!-- Left side - Login Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Runa Maki</h1>
                    <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900">Bienvenido de vuelta</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        ¿No tienes una cuenta?
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Regístrate aquí
                        </a>
                    </p>
                </div>

                <div class="mt-8">
                    <!-- Credenciales de Prueba -->
                    <div class="mb-6 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-sm font-semibold text-gray-700">Credenciales de Prueba</h3>
                        </div>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center p-2 bg-white rounded hover:bg-indigo-50 transition cursor-pointer" onclick="fillLogin('admin@runamaki.com', 'admin123')">
                                <div>
                                    <p class="font-medium text-gray-800">👑 Administrador</p>
                                    <p class="text-gray-600">admin@runamaki.com</p>
                                </div>
                                <span class="text-indigo-600 text-xs">Clic para usar</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-white rounded hover:bg-indigo-50 transition cursor-pointer" onclick="fillLogin('maria@example.com', 'admin123')">
                                <div>
                                    <p class="font-medium text-gray-800">👩 María Quispe</p>
                                    <p class="text-gray-600">maria@example.com</p>
                                </div>
                                <span class="text-indigo-600 text-xs">Clic para usar</span>
                            </div>
                            <div class="flex justify-between items-center p-2 bg-white rounded hover:bg-indigo-50 transition cursor-pointer" onclick="fillLogin('carlos@example.com', 'admin123')">
                                <div>
                                    <p class="font-medium text-gray-800">👨 Carlos Mendoza</p>
                                    <p class="text-gray-600">carlos@example.com</p>
                                </div>
                                <span class="text-indigo-600 text-xs">Clic para usar</span>
                            </div>
                            <p class="text-gray-500 italic mt-2 pt-2 border-t border-indigo-200">Contraseña para todos: <span class="font-mono font-semibold text-gray-700">admin123</span></p>
                        </div>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('¡Oops! Algo salió mal.') }}
                            </div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.perform') }}" class="space-y-6">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Correo electrónico
                            </label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                    value="{{ old('email') }}">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Contraseña
                            </label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" autocomplete="current-password" required
                                    class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember_me" name="remember" type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                    Recordarme
                                </label>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Right side - Image and Features -->
        <div class="hidden lg:block relative w-0 flex-1">
            <div class="login-gradient">
                <div class="login-overlay"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-white p-12">
                    <h2 class="text-4xl font-bold mb-8">Intercambia Habilidades en tu Comunidad</h2>
                    <div class="grid grid-cols-2 gap-8 max-w-2xl">
                        <div class="flex items-start">
                            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Comunidad Local</h3>
                                <p class="text-sm text-gray-300">Conecta con personas de tu localidad que comparten intereses</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Intercambio Justo</h3>
                                <p class="text-sm text-gray-300">Sistema de puntos Runa para intercambios equitativos</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Diversas Categorías</h3>
                                <p class="text-sm text-gray-300">Encuentra y comparte habilidades en múltiples áreas</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold mb-1">Sistema de Confianza</h3>
                                <p class="text-sm text-gray-300">Valoraciones y reputación para mayor seguridad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            
            // Optional: scroll to form
            document.getElementById('email').focus();
        }
    </script>
</body>
</html>