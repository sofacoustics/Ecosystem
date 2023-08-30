<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a dataset
        </h2>
    </x-slot>
    @auth
        <p>TODO: implement dataset creation code/form</p>
    @endauth 
    @guest
        <p>You are not logged in, so you cannot use this functionality</p>
    @endguest
</x-app-layout>