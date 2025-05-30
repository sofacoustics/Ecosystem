<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			The SONICOM Ecosystem contains {{ @count($allTools) }} tools
		</h2>
		@can('create', \App\Models\Tool::class)
			<x-button method="GET" action="{{ route('tools.create') }}" class="inline">
				Add a New Tool
			</x-button>
		@endcan				
	</x-slot>

	@if ($message = Session::get('success'))
			<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
			</div>
	@endif
		
	<livewire:tool-table-filter />

</x-app-layout>

