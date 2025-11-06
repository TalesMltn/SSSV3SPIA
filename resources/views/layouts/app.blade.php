<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema Académico') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind + Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">

    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Page Heading (opcional, con estilo bonito) -->
        @isset($header)
            <header class="mb-10">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endisset

        <!-- Contenido principal -->
        <div class="animate-fade-in">
            @yield('content')
        </div>
    </main>



</body>
</html>