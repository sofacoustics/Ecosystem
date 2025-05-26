<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$relatedidentifierable" />
	</x-slot>
	<div>
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifier->relatedidentifierable" :relatedidentifier=$relatedidentifier />
	</div>
</x-app-layout>
