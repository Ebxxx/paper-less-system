<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Load emoji picker before other scripts -->
        <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.css">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Stack Styles -->
        @stack('styles')

        <!-- Add Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <!-- Add emoji picker as a dropdown -->
        <div id="emojiPicker" class="emoji-picker-dropdown hidden fixed z-50">
            <div class="relative">
                <!-- Main dropdown container -->
                <div class="relative bg-white rounded-lg border border-gray-200 shadow-lg overflow-hidden">
                    <!-- Emoji picker container -->
                    <div class="h-[400px]">
                        <emoji-picker></emoji-picker>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-h-screen bg-gray-100">
            @include('layouts.userSidebar')
        </div>
    </body>
</html>
