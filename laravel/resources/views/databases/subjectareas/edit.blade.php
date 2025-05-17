<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$subjectareaable" />
	</x-slot>
	<div>
		<livewire:subject-area-form :subjectareaable="$subjectarea->subjectareaable" :subjectarea=$subjectarea />
	</div>
</x-app-layout>
