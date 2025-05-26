<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$relatedidentifierable" />
	</x-slot>
	<h3>Relations</h3>
	<p>List of relations with the tool:</p>
	<x-relatedidentifier.list :relatedidentifierable=$relatedidentifierable />
	
	<livewire:related-identifier-form :relatedidentifierable="$relatedidentifierable" />

</x-app-layout>
