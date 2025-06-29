<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$relatedidentifierable" />
	</x-slot>
	@can('update', $relatedidentifierable)
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifier->relatedidentifierable" :relatedidentifier=$relatedidentifier />
	@else
		You can not edit this relation because you do not own the corresponding tool. 
	@endcan
</x-app-layout>
