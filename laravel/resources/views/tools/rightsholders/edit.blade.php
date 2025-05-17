<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$rightsholder->rightsholderable" />
	</x-slot>
	<div>
		<livewire:rightsholder-form :rightsholderable="$rightsholder->rightsholderable" :rightsholder=$rightsholder />
	</div>
</x-app-layout>
