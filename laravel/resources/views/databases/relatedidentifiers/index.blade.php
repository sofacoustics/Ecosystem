<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$relatedidentifierable" />
	</x-slot>
	<h3>Relations</h3>
	<p>List of relations with the database:</p>
	<x-relatedidentifier.list :relatedidentifierable=$relatedidentifierable />
	
	@can('update', $relatedidentifierable)
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifierable" />
	@endcan
</x-app-layout>
