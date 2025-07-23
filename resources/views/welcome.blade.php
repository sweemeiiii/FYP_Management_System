<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | FYPMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .fade-in {
            animation: fadeIn 1.5s ease-in-out both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-blue-100 via-white to-purple-100 min-h-screen font-sans flex items-center justify-center">

    <div class="bg-white/70 backdrop-blur-md shadow-xl rounded-2xl px-8 py-10 max-w-2xl w-full mx-4 text-center fade-in">
        <!-- Logo placeholder -->
        <div class="mb-6">
            <div class="w-16 h-16 mx-auto bg-blue-500 text-white rounded-full flex items-center justify-center text-2xl font-bold shadow-lg">
                S
            </div>
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-blue-700 mb-4">Welcome to FYPMS</h1>
        <p class="text-lg text-gray-700 mb-8">
            Final Year Project Management System helps students, supervisors, and coordinators collaborate efficiently.
        </p>

        <div class="flex flex-col md:flex-row items-center justify-center gap-4">
            <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-all duration-200 shadow-md">
                Login
            </a>
            <a href="#" onclick="alert('Registration is only available through the admin.'); return false;" class="px-6 py-3 rounded-xl bg-white border border-gray-300 text-gray-800 font-semibold hover:bg-gray-100 transition-all duration-200 shadow-md">
                Register
            </a>
        </div>

        <footer class="mt-10 text-sm text-gray-500">
            &copy; {{ date('Y') }} FYPMS. All rights reserved.
        </footer>
    </div>

</body>
</html>
