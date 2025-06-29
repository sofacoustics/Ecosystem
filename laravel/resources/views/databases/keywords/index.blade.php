<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$keywordable" />
	</x-slot>
	
	<h3>Keywords</h3>
	<p>Keyword(s) describing the subject focus of the database:</p>
	<x-keyword.list :keywordable=$keywordable />

	@can('update', $keywordable)
			<livewire:keyword-form :keywordable="$keywordable" />
	@endcan



</x-app-layout>
