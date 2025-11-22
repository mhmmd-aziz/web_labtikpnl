<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

   <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) <script src="https://cdn.tailwindcss.com"></script> -->


</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('filament.admin.pages.dashboard') }}"> {{-- Ganti dengan logo jika ada --}}
                                <span class="text-xl font-bold text-gray-800 dark:text-white">LabTIK-PNL</span> 
                            </a>
                        </div>
                    </div>
                     </div>
            </div>
        </nav>

        <main>
            @yield('content') </main>
    </div>

    @stack('scripts') </body>
</html>