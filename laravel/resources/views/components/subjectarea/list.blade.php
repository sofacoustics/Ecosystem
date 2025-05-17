<ul class="list-disc list-inside">
	@forelse($subjectareaable->subjectareas as $subjectarea)
			<li>
				@can('update', $subjectareaable)
					<x-button method="GET" action="{{ route('subjectareas.edit', [$subjectarea]) }}" class="inline">
						Edit
					</x-button>
				@endcan
				@can('delete', $subjectareaable)
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