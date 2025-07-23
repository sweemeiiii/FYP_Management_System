<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <footer class="text-center text-sm text-gray-500 py-2 bg-white border-t">
           © 2025 FYPMS. All rights reserved.
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const textarea = document.getElementById('message');
                const wordCountDisplay = document.getElementById('word-count');
                const maxWords = 1500;

                if (textarea && wordCountDisplay) {
                    textarea.addEventListener('input', () => {
                        const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
                        const count = words.length;
                        wordCountDisplay.textContent = `${count} / ${maxWords} words`;

                        if (count > maxWords) {
                            wordCountDisplay.classList.add('text-danger');
                        } else {
                            wordCountDisplay.classList.remove('text-danger');
                        }
                    });
                }
            });
        </script>

    </body>
</html>
