<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$relatedidentifierable" />
	</x-slot>
	<div>
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifier->relatedidentifierable" :relatedidentifier=$relatedidentifier />
	</div>
</x-app-layout>
