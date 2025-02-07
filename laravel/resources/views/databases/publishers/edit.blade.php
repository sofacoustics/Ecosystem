{{--
    Edit publishers
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$publisher->database" />
    </x-slot>
    <div>

			<livewire:publisher-form :database="$publisher->database" :publisher=$publisher />
    </div>
</x-app-layout>
