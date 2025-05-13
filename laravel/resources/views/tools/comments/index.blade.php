<x-app-layout>
	<x-slot name="header">
		<x-tools.header :tool=$tool />
	</x-slot>
	<h3>New comment:</h3>
	<livewire:comment-form :tool=$tool />
</x-app-layout>
