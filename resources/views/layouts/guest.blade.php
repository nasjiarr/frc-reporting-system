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

        .bg-left-pattern {
            position: relative;
        }

        .bg-left-pattern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('{{ asset("images/gedung-frc.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: brightness(0.5) contrast(1.2);
            z-index: -1;
        }
    </style>
</head>

<body class="bg-slate-50 dark:bg-gray-900 antialiased text-slate-800 dark:text-gray-100 selection:bg-indigo-100 dark:selection:bg-indigo-900 selection:text-indigo-900 dark:selection:text-indigo-100 transition-colors duration-200">
    <div class="min-h-screen flex flex-col sm:flex-row">

        <!-- Sisi Kiri: Brand Area -->
        <div class="w-full sm:w-3/5 bg-left-pattern flex flex-col justify-center items-center px-8 sm:px-12 py-12 sm:py-16">
            <div class="text-center">

                <!-- BANTALAN LOGO YANG TELAH DIRAPIKAN -->
                <div class="flex items-center justify-center w-32 h-32 sm:w-40 sm:h-40 md:w-48 md:h-48 bg-white rounded-full shadow-xl mb-6 sm:mb-8 mx-auto">
                    <!-- object-contain memastikan logo tidak terdistorsi -->
                    <img src="{{ asset('images/logo-frc.png') }}" alt="Logo FRC" class="w-20 sm:w-24 md:w-32 h-auto object-contain drop-shadow-sm">
                </div>

                <h1 class="text-4xl sm:text-5xl font-bold text-white tracking-tight mb-3 sm:mb-4">
                    FRC
                </h1>

                <p class="text-lg sm:text-xl text-indigo-100 font-medium mb-2">
                    Field Research Center Report
                </p>

                <p class="text-sm text-indigo-200">
                    Supporting Research Through Better Facilities
                </p>
            </div>
        </div>

        <!-- Sisi Kanan: Form Area -->
        <div class="w-full sm:w-2/5 bg-white dark:bg-gray-800 flex flex-col justify-center items-center px-8 py-12 sm:py-16 relative transition-colors duration-200">

            <div class="w-full max-w-md">

                <div class="absolute top-0 left-0 w-full h-1 bg-indigo-600"></div>

                {{ $slot }}

            </div>

            <div class="mt-8 sm:mt-10 text-center text-xs text-gray-400 dark:text-gray-500">
                &copy; {{ date('Y') }} Field Research Center (FRC).<br>Sekolah Vokasi UGM.
            </div>
        </div>

    </div>
</body>

</html>