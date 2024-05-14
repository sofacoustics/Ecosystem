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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
	<!-- https://stackoverflow.com/questions/45279612/including-a-css-file-in-a-blade-template -->
	@livewireStyles
    </head>
    <body class="font-sans antialiased">
        <!-- https://tailwindcss.com/docs/customizing-colors -->
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="shadow">
                    <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{-- <livewire:delivery-history /> --}}
            {{-- <livewire:message-component /> --}}


            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
	</div>
	@livewireScripts
    </body>
</html>
