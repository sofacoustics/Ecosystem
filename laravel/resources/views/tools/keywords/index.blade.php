<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$keywordable" />
	</x-slot>
	<h3>Keywords</h3>
	<p>Keyword(s) describing the subject focus of the tool:</p>
	
	<x-keyword.list :keywordable=$keywordable />

	@can('update', $keywordable)
		<livewire:keyword-form :keywordable="$keywordable" />
	@endcan
	

</x-app-layout>
