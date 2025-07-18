<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database :tabTitle=$tabTitle />
	</x-slot>

	@if(isset($tabTitle))
		<h2>{{ $tabTitle }}</h2>
	@endif
	@section('tabTitle', " | " . $tabTitle)

	<livewire:database-radar-actions :database="$database" />

</x-app-layout>
