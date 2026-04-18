<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Sistem Pelaporan FRC') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-frc.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 antialiased text-slate-800 selection:bg-indigo-100 selection:text-indigo-900">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div class="mb-8 text-center">
            <a href="/">
                <img src="{{ asset('images/logo-frc.png') }}" alt="Logo FRC" class="h-32 md:h-40 w-auto mx-auto mb-4">

                <h1 class="text-2xl font-bold text-indigo-700 tracking-tight">
                    FRC<span class="text-slate-800">Report</span>
                </h1>
            </a>
            <p class="mt-2 text-sm text-gray-500 font-medium">Sistem Informasi Pelaporan Terpadu</p>
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-white border border-gray-200 shadow-sm sm:rounded-xl overflow-hidden relative">

            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-600"></div>

            {{ $slot }}

        </div>

        <div class="mt-10 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Field Research Center (FRC).<br>Sekolah Vokasi UGM.
        </div>
    </div>
</body>

</html>