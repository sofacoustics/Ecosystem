<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$keywordable" />
	</x-slot>
	
	<h3>Keywords</h3>
	<p>Keyword(s) describing the subject focus of the tool:</p>
	<x-keyword.list :keywordable=$keywordable />

	<livewire:keyword-form :keywordable="$keywordable" />

</x-app-layout>
