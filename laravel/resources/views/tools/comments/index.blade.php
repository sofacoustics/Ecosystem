<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$commentable" />
	</x-slot>
	
	@can('create', \App\Models\Tool::class)
		<livewire:comment-form :commentable="$commentable" />
	@else
		You can not add comments because your account is not ORCiD approved.
	@endcan
</x-app-layout>
