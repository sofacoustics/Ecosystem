<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">

		{{--

			Set the title

			The header.blade.php uses @section('pageTitle', "the title we want to display") to set a different one from
			the default, which we set here to config('app.name')

			Additionally, a view can then set the 'tabTitle'

		--}}
		<title> @yield('pageTitle', config('app.name')) @yield('tabTitle') </title>
		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

		<!-- Scripts -->
		@vite(['resources/css/app.css', 'resources/js/app.js'])
	<!-- https://stackoverflow.com/questions/45279612/including-a-css-file-in-a-blade-template -->
	@livewireStyles
	</head>
	<body class="font-sans antialiased">
		<!-- Display 'status' session contents -->
		@if(session('success'))
			<div x-data="{ show: true }"
				 x-init="setTimeout(() => show = false, 3000)"
				 x-show="show">
				<x-alert type='success'>
					{{ session('success') }}
				</x-alert>
			</div>
		@endif
		@if(session('status'))
			<div x-data="{ show: true }"
				 x-init="setTimeout(() => show = false, 3000)"
				 x-show="show">
				<x-alert type='status'>
					{{ session('status') }}
				</x-alert>
			</div>
		@endif
		<!-- Display 'status-messages' events -->
		<livewire:status-messages />

		<!-- https://tailwindcss.com/docs/customizing-colors -->
		<div class="min-h-screen flex flex-col">
			@include('layouts.navigation')

			<!-- Page Heading -->
			@if (isset($header))
				<header class="shadow">
					<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
						{{ $header }}
					</div>
				</header>
			@endif
			<!-- errors -->
			@if(session('error'))
				<x-alert type='error'>
					{{ session('error') }}
				</x-alert>
			@endif
			<!-- validation errors -->
			@if ($errors->any())
				<div>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<!-- Page Content -->
			<main class="grow">
				{{ $slot }}
			</main>
			@include('layouts.footer')
		</div>
		@livewireScripts
	</body>
</html>
