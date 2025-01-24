<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a new database
        </h2>
    </x-slot>

	<livewire:database-form />

    @guest
        <p>BUG: Please report that you got to this page. This should only be accessable by authenticated users.</p>
    @endguest
</x-app-layout>
