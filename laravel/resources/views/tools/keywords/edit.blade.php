<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$keywordable" />
	</x-slot>
	<div>
		<livewire:keyword-form :keywordable="$keyword->keywordable" :keyword=$keyword />
	</div>
</x-app-layout>
