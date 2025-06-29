<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$subjectareaable" />
	</x-slot>
	@can('update', $subjectareaable)
		<livewire:subject-area-form :subjectareaable="$subjectarea->subjectareaable" :subjectarea=$subjectarea />
	@else
		You can not edit this subject area because you do not own the corresponding database. 
	@endcan
</x-app-layout>
