{{--
    Edit keyword
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$keyword->database" />
    </x-slot>
    <div>

			<livewire:keyword-form :database="$keyword->database" :keyword=$keyword />
    </div>
</x-app-layout>
