<x-app-layout>
	<x-slot name="header">
			<x-tool.header :tool=$tool :tabTitle=$tabTitle />
	</x-slot>

	@if(isset($tabTitle))
		<h2>{{ $tabTitle }}</h2>
	@endif
	@section('tabTitle', " | " . $tabTitle)

	<livewire:tool-radar-status :tool="$tool" />

	<livewire:tool-radar-actions :tool="$tool" />

</x-app-layout>
