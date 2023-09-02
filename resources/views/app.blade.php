<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'LLM Assistant') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- For the classic favicon -->
        <link rel="icon" href="/favicon.ico" type="image/x-icon">

        <!-- For modern browsers - 32x32 -->
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">

        <!-- For modern browsers - 16x16 -->
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

        <!-- For Apple devices -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

        <!-- For Android Chrome - 192x192 -->
        <link rel="icon" type="image/png" sizes="192x192" href="/android-chrome-192x192.png">

        <!-- For Android Chrome - 512x512 -->
        <link rel="icon" type="image/png" sizes="512x512" href="/android-chrome-512x512.png">

        <!-- Web Manifest -->
        <link rel="manifest" href="/site.webmanifest">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
