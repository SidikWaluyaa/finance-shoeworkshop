<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FinanceSW') }}</title>

        {{-- Favicon --}}
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        {{-- Google Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F8F9FA] min-h-screen overflow-x-hidden">
        {{-- Background Pattern Layer --}}
        <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.15]" 
             style="background-image: url('{{ asset('images/shoe-pattern.png') }}'); background-repeat: repeat; background-size: 500px;">
        </div>

        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-0">
            <div class="w-full sm:max-w-[480px] bg-white shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] rounded-[32px] p-8 sm:p-12 border border-slate-100 transition-all duration-500">
                <div class="flex flex-col items-center mb-10">
                    <img src="{{ asset('logo.png') }}" alt="Shoe Workshop Logo" class="h-20 sm:h-24 w-auto mb-8 hover:scale-105 transition-transform duration-300">
                    {{ $slot }}
                </div>
            </div>

            <p class="mt-8 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                &copy; {{ date('Y') }} Shoe Workshop Internal System
            </p>
        </div>

        <style>
            body {
                background-color: #fcfcfc;
            }
        </style>
    </body>
</html>
