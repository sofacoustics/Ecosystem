{{--
    Edit creators
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$creator->database" />
    </x-slot>
    <div>

			<livewire:creator-form :database="$creator->database" :creator=$creator />
    </div>
</x-app-layout>
