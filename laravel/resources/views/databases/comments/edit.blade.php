<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$comment->commentable" />
	</x-slot>
	<div>
		<livewire:comment-form :commentable="$comment->commentable" :comment=$comment />
	</div> 
</x-app-layout>
