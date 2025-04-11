<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <h3>FAILED!</h3>
		
		<a href="{{ route('profile.edit') }}">Continue</a>
</x-app-layout>
