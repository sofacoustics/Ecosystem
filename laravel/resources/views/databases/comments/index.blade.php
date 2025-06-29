<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$commentable" />
	</x-slot>
	
	@can('create', \App\Models\Database::class)
		<livewire:comment-form :commentable="$commentable" />
	@else
		You can not add comments because your account is not ORCiD approved.
	@endcan
</x-app-layout>
