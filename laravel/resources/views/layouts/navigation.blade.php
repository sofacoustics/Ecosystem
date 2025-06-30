	{{-- https://v1.tailwindcss.com/components/navigation --}}
@if(str_contains(config('app.name'),"(dev)"))
	<nav class="flex items-center justify-between flex-wrap bg-orange-500  p-6">
@else
	<nav class="flex items-center justify-between flex-wrap bg-white  p-6">
@endif

	<div class="flex items-center flex-shrink-0 text-black mr-6">
		<a href="/"><x-application-logo /></a>
		<a href="/"><span class="font-semibold text-xl tracking-tight">SONICOM Ecosystem</span></a>
	</div>
	<div class="block lg:hidden">
		<button class="flex items-center px-3 py-2 border rounded text-black-200 border-teal-400 hover:text-white hover:border-white">
			<svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
		</button>
	</div>

	<div class="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
		<div class="bg-red text-sm lg:flex-grow">
			@foreach(\App\Models\MenuItem::whereNull('parent_id')->get() as $menuItem)
				@if (!Auth::check() && $menuItem->authenticated > 0)
					{{-- Do not show menu items which are restricted to authenticated users, unless they are logged in --}}
				@elseif (Auth::check() && !auth()->user()->hasRole('admin') && $menuItem->authenticated == 2)
					{{-- Do not show 'admin' menu --}}
				@else
					<a href="{{$menuItem->url()}}" class="block mt-4 lg:inline-block lg:mt-0 text-black-200 hover:text-black-300 mr-4">
						{{$menuItem->title}}
					</a>
				@endif
			@endforeach
		</div>

			<!-- Settings Dropdown -->
		@auth
		<div class="hidden sm:flex sm:items-center sm:ml-6">
			<x-dropdown align="right" width="48">
				<x-slot name="trigger">
						<button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
								<div>{{ Auth::user()->name }}</div>

								<div class="ml-1">
										<svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
												<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
										</svg>
								</div>
						</button>
				</x-slot>

				<x-slot name="content">
					<x-dropdown-link :href="route('profile.edit')">
						{{ __('Profile') }}
					</x-dropdown-link>

						<!-- Authentication -->
					<form method="POST" action="{{ route('logout') }}">
						@csrf
						<x-dropdown-link :href="route('logout')"
							onclick="event.preventDefault(); this.closest('form').submit();">
							{{ __('Log Out') }}
						</x-dropdown-link>
					</form>
				</x-slot>
			</x-dropdown>
		</div>
		@else
			<a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>
			@if (Route::has('register'))
				<a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
			@endif
		@endauth
	</div>
</nav>