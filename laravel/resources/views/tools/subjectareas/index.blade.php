<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$subjectareaable" />
	</x-slot>
	<h3>Subject Areas</h3>
	<p>List of scientific research areas that the tool can be assigned to:</p>
	<x-subjectarea.list :subjectareaable=$subjectareaable />
	
	<livewire:subject-area-form :subjectareaable="$subjectareaable" />

</x-app-layout>
