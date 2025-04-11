{{--
    Edit Rightsholder
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$rightsholder->database" />
    </x-slot>
    <div>

			<livewire:rightsholder-form :database="$rightsholder->database" :rightsholder=$rightsholder />
    </div>
</x-app-layout>
