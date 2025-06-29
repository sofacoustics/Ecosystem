<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$relatedidentifierable" />
	</x-slot>
	@can('update', $relatedidentifierable)
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifier->relatedidentifierable" :relatedidentifier=$relatedidentifier />
	@else
		You can not edit this relation because you do not own the corresponding database. 
	@endcan
</x-app-layout>
