<div>
	<x-mail.header :admin="$admin" :user="$comment->commentable->user" />
	@if($comment->commentable_type == "App\Models\Database")
		<p>The database <a href="{{ route('databases.show', $comment->commentable->id) }}">{{ $comment->commentable->title }}</a> has been commented on by the user {{ $comment->user->name }}</p>
	@else
		<p>The tool <a href="{{ route('tools.show', $comment->commentable->id) }}">{{ $comment->commentable->title }}</a> has been commented on by the user {{ $comment->user->name }}:</p>
	@endif
	<pre>{{ $comment->text }}</pre>
	<x-mail.footer :admin="$admin" />
</div>
