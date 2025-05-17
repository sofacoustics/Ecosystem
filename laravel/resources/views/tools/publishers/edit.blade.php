<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$publisherable" />
	</x-slot>
	<div>
		<livewire:publisher-form :publisherable="$publisher->publisherable" :publisher=$publisher />
	</div>
</x-app-layout>
