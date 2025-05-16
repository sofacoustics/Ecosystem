<x-app-layout>
<x-slot name="header">
	<x-tool.header :tool=$tool />
</x-slot>

	<h2>Metadata</h2>
		@can('update', $database)
			@if(count($database->creators)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.creators', $database->id) }}">Edit</x-button></small>
					Creators:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.creators', $database->id) }}">Add creators</x-button></small>
				</h3>
			@endif
		@else
			<h3>Creators:</h3>
		@endcan

		<ul class="list-disc list-inside">
			@forelse ($database->creators as $creator)
			<li><b>Name</b>: {{ $creator->creatorName }}
				@if ($creator->givenName != null) <b>Given Name</b>: {{ $creator->givenName }}@endif 
				@if($creator->familyName != null) <b>Family Name</b>: {{ $creator->familyName }}@endif
				@if ($creator->nameIdentifier != null) <b>{{ $creator->nameIdentifierScheme($creator->nameIdentifierSchemeIndex) }}</b>: {{ $creator->nameIdentifier }}@endif
				@if ($creator->creatorAffiliation != null) <b>Affiliation</b>: {{ $creator->creatorAffiliation }}@endif
				@if ($creator->affiliationIdentifier != null) <b>{{ $creator->affiliationIdentifierScheme }}</b>: {{ $creator->affiliationIdentifier }}@endif 
			</li>
			@empty
				@cannot('update', $database)
					<li>No creators defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $database)
			@if(count($database->publishers)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.publishers', $database->id) }}">Edit</x-button></small>
					Publishers:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.publishers', $database->id) }}">Add publishers</x-button></small>
				</h3>
			@endif
		@else
			<h3>Publishers:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->publishers as $publisher)
			<li><b>Name</b>: {{ $publisher->publisherName }}
				@if ($publisher->nameIdentifier != null) <b>{{ $publisher->nameIdentifierScheme($publisher->nameIdentifierSchemeIndex) }}</b>: 
					@if ($publisher->schemeURI != null) <a href="{{ $publisher->schemeURI }}"> @endif
					{{ $publisher->nameIdentifier }}
					@if ($publisher->schemeURI != null) </a> @endif
				@endif
			</li>
			@empty
				@cannot('update', $database)
					<li>No publishers defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $database)
			@if(count($database->subjectareas)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.subjectareas', $database->id) }}">Edit</x-button></small>
					Subject Areas:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.subjectareas', $database->id) }}">Add subject areas</x-button></small>
				</h3>
			@endif
		@else
			<h3>Subject Areas:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->subjectareas as $subjectarea)
			<li><b>{{ \App\Models\Database::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}</b>
					@if ($subjectarea->additionalSubjectArea != null) ({{ $subjectarea->additionalSubjectArea }}) @endif
			</li>
			@empty
				@cannot('update', $database)
					<li>No subject areas defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $database)
			@if(count($database->rightsholders)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.rightsholders', $database->id) }}">Edit</x-button></small>
				Rightsholders:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.rightsholders', $database->id) }}">Add rightholders</x-button></small>
				<h3>
			@endif
		@else
			<h3>Rightsholders:</h3>			
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->rightsholders as $rightsholder)
			<li><b>Name</b>: {{ $rightsholder->rightsholderName }}
				@if ($rightsholder->nameIdentifier != null) <b>{{ $rightsholder->nameIdentifierScheme($rightsholder->nameIdentifierSchemeIndex) }}</b>: 
					@if ($rightsholder->schemeURI != null) <a href="{{ $rightsholder->schemeURI }}"> @endif
					{{ $rightsholder->nameIdentifier }}
					@if ($rightsholder->schemeURI != null) </a> @endif
				@endif
			</li>
			@empty
				@cannot('update', $database)
					<li>No rightsholder defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $database)
			@if(count($database->rightsholders)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.keywords', $database->id) }}">Edit</x-button></small>
				Keywords:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.keywords', $database->id) }}">Add keywords</x-button></small>
				</h3>
			@endif
		@else
			<h3>Keywords:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->keywords as $keyword)
			<li>
				<b>{{ $keyword->keywordName }}</b> 
				@if($keyword->classificationCode)
					(@if ($keyword->schemeURI != null)<a href="{{ $keyword->schemeURI }}">@endif{{ \App\Models\Keyword::keywordScheme($keyword->keywordSchemeIndex)}}@if ($keyword->schemeURI != null)</a>@endif: 
					@if ($keyword->valueURI != null)<a href="{{ $keyword->valueURI }}">@endif{{ $keyword->classificationCode }}@if ($keyword->valueURI != null)</a>@endif)
				@endif
			</li>
			@empty
				@cannot('update', $database)
					<li>No keywords defined.</li>
				@endcan
			@endforelse
		</ul>

	<h2>Metadata</h2>
		<p><b>Description:</b> {{ $tool->description }}</p>
		@if($tool->filesize())
			<p><b>File Name:</b> <a href="{{ asset($tool->url()) }}" download>{{ $tool->filename }}</a></p>
			<p><b>File Size:</b> {{ $tool->filesize() }} bytes 
				@if($tool->filesize() > 10240)
				= {{ round($tool->filesize() / 1024, 2) }} kbytes 
					@if($tool->filesize() > (1024*10240))
						= {{ round($tool->filesize() / 1024 / 1024, 2)  }} MB 
						@if($tool->filesize() > (1024*102410240))
							= {{ round($tool->filesize() / 1024 / 1024 / 1024, 2) }} GB
						@endif
					@endif
				@endif
			</p>
		@else
			<p><b>File Name:</b> file not provided yet, upload a file</p>
		@endif
		<p>Tool created: {{ $tool->created_at }}</p>
		<p>Tool updated: {{ $tool->updated_at }}</p>
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
