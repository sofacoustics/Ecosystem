<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$keywordable" />
	</x-slot>
	<div>
		<livewire:keyword-form :keywordable="$keyword->keywordable" :keyword=$keyword />
	</div>
</x-app-layout>
