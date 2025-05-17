<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$subjectareaable" />
	</x-slot>
	<h3>Subject Areas</h3>
	<p>List of scientific research areas that the database can be assigned to:</p>
	<x-subjectarea.list :subjectareaable=$subjectareaable />
	
	<livewire:subject-area-form :subjectareaable="$subjectareaable" />

</x-app-layout>
