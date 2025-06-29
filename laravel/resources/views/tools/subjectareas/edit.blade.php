<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$subjectareaable" />
	</x-slot>
	@can('update', $creatorable)
		<livewire:subject-area-form :subjectareaable="$subjectarea->subjectareaable" :subjectarea=$subjectarea />
	@else
		You can not edit this subject area because you do not own the corresponding tool. 
	@endcan
</x-app-layout>
