<x-app-layout>
	<x-slot name="header">
		Services
	</x-slot>
	@section('tabTitle', " | " . $tabTitle)

	<livewire:service-status />

</x-app-layout>
