<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$rightsholder->rightsholderable" />
	</x-slot>
	@can('update', $rightsholderable)
		<livewire:rightsholder-form :rightsholderable="$rightsholder->rightsholderable" :rightsholder=$rightsholder />
	@else
		You can not edit this rightsholder because you do not own the corresponding tool. 
	@endcan
</x-app-layout>
