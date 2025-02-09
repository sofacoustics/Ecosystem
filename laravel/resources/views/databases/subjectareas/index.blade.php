<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Subject Area:</h3>

    <ul class="list-disc list-inside">
        @forelse($database->subjectareas as $subjectarea)
					<li><b>{{ \App\Models\Database::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}</b>
							@if ($subjectarea->additionalSubjectArea != null) : {{ $subjectarea->additionalSubjectArea }} @endif
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
          </li>
        @empty
            <li>No subject areas defined.</li>
        @endforelse
    </ul>

    <livewire:subjectarea-form :database=$database />

</x-app-layout>
