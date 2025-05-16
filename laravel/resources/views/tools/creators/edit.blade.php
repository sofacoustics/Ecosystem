<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$commentable" />
	</x-slot>
	<div>
		<livewire:creator-form :tool="$creator->tool" :creator=$creator />
	</div>
</x-app-layout>
