<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Login' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class=" antialiased bg-cover bg-center bg-fixed" style="background-image: url({{ asset('assets/img/login-background.jpg') }}) ">
    
    <main class="min-h-screen flex items-center justify-center ">
        {{ $slot }}
        
    </main>
    
    @livewireScripts
</body>
</html>