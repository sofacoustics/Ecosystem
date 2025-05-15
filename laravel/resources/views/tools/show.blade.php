<x-app-layout>
<x-slot name="header">
	<x-tool.header :tool=$tool />
</x-slot>

	<h2>Metadata</h2>
		<p><b>Title:</b> {{ $tool->title}}</p>
		<p><b>Description:</b> {{ $tool->description }}</p>
		<p><b>File Name:</b> <a href="{{ asset($tool->url()) }}" download>{{ $tool->filename }}</a></p>

	<h2>Comments</h2>
		@if(count($tool->comments)==0)
			<p>No comments found.</p>
		@else
			<b>{{ count($tool->comments) }}</b> comments found:
			<ul class="list-disc list-inside">
			@foreach($tool->comments as $comment)
				<li>
					@auth
						@if ($comment->user_id == Auth::id()) 
							<x-button method="DELETE" class="inline" action="{{ route('comments.destroy', $comment) }}">Delete</x-button>
							<x-button method="GET" class="inline" action="{{ route('comments.edit', $comment) }}" >Edit</x-button>
						@endif
					@endauth
					<b> {{ $comment->user->name }} </b><small>({{ $comment->created_at }})</small>: {{ $comment->text }}
				</li>
			@endforeach
			</ul>
		@endif
		@can('create', \App\Models\Database::class)
			<x-button method="GET" class="inline" action="{{ route('tools.comments', $tool->id) }}">Add a comment</x-button>
		@endcan


@env('local')
    <ul class="list-disc list-inside">
    </div>
@endenv

</x-app-layout>
