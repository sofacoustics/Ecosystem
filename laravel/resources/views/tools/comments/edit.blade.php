<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$commentable" />
	</x-slot>
	@can('update', $comment)
		<livewire:comment-form :commentable="$comment->commentable" :comment=$comment />
	@else
		You can not edit this comment because you do not own it. 
	@endcan 
</x-app-layout>
