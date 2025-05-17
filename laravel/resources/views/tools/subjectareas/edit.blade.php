<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$subjectareaable" />
	</x-slot>
	<div>
		<livewire:subject-area-form :subjectareaable="$subjectarea->subjectareaable" :subjectarea=$subjectarea />
	</div>
</x-app-layout>
