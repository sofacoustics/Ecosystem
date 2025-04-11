<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database />
	</x-slot>
	<h3>Subject Areas</h3>

		<p>List of scientific research areas that the database can be assigned to:</p>
		
	<ul class="list-disc list-inside">
	  @forelse($database->subjectareas as $subjectarea)
				<li>
					@can('update', $database)
						<x-button method="GET" action="{{ route('subjectareas.edit', [$subjectarea]) }}" class="inline">
							Edit
						</x-button>
					@endcan
					@can('delete', $database)
						<x-button method="DELETE" action="{{ route('subjectareas.destroy', [$subjectarea]) }}" class="inline">
							Delete
						</x-button>
					@endcan
					<b>{{ \App\Models\Database::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}</b>
					@if ($subjectarea->additionalSubjectArea != null) ({{ $subjectarea->additionalSubjectArea }}) @endif
				</li>
			@empty
				<li>No subject areas defined yet.</li>
			@endforelse
	</ul>

	<livewire:subjectarea-form :database=$database />

</x-app-layout>
