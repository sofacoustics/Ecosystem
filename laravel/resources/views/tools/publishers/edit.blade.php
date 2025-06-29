<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$publisherable" />
	</x-slot>
	@can('update', $publisherable)
		<livewire:publisher-form :publisherable="$publisher->publisherable" :publisher=$publisher />
	@else
		You can not edit this publisher because you do not own the corresponding tool. 
	@endcan
</x-app-layout>
