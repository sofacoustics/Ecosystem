<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$commentable" />
	</x-slot>
	
	<h3>New comment:</h3>
	<livewire:comment-form :commentable="$commentable" />
</x-app-layout>
