{{--
    Edit subjectarea
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$subjectarea->database" />
    </x-slot>
    <div>

			<livewire:subjectarea-form :database="$subjectarea->database" :subjectarea=$subjectarea />
    </div>
</x-app-layout>
