<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database />
	</x-slot>
	<h2>Metadata:</h2>
		<h3>
			@auth
				@if ($database->user_id == Auth::id())
					<x-button method="GET" class="inline" action="{{ route('databases.creators', $database->id) }}">Edit</x-button>
				@endif
			@endauth
			Creators:</h3>
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
				<li>No creators defined.</li>
			@endforelse
		</ul>

		<h3>
			@auth
				@if ($database->user_id == Auth::id()) 
					<x-button method="GET" class="inline" action="{{ route('databases.publishers', $database->id) }}">Edit</x-button>
				@endif
			@endauth
			Publishers:</h3>
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
				<li>No publishers defined.</li>
			@endforelse
		</ul>

		<h3>
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
					<x-button method="GET" class="inline" action="{{ route('databases.subjectareas', $database->id) }}">Edit</x-button>
				@endif
			@endauth
			Subject Areas:</h3>
		<ul class="list-disc list-inside">
			@forelse ($database->subjectareas as $subjectarea)
			<li><b>{{ \App\Models\Database::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}</b>
					@if ($subjectarea->additionalSubjectArea != null) ({{ $subjectarea->additionalSubjectArea }}) @endif
			</li>
			@empty
				<li>No subject areas defined.</li>
			@endforelse
		</ul>

		<h3>
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
					<x-button method="GET" class="inline" action="{{ route('databases.rightsholders', $database->id) }}">Edit</x-button>
				@endif
			@endauth
			Rightsholders:</h3>
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
				<li>No rightsholder defined.</li>
			@endforelse
		</ul>

		<h3>
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
				<x-button method="GET" class="inline" action="{{ route('databases.keywords', $database->id) }}">Edit</x-button>
				@endif
			@endauth
			Keywords:</h3>
		<ul class="list-disc list-inside">
			@forelse ($database->keywords as $keyword)
			<li><b> {{ $keyword->keywordName }} </b> (
				@if ($keyword->schemeURI != null)<a href="{{ $keyword->schemeURI }}"> @endif
					{{$keyword->keywordScheme($keyword->keywordSchemeIndex)}}
				@if ($keyword->schemeURI != null)</a> @endif </b>: 
				@if ($keyword->valueURI != null)<a href="{{ $keyword->valueURI }}"> @endif
				{{ $keyword->classificationCode }}
				@if ($keyword->valueURI != null)</a> @endif )
			</li>
			@empty
				<li>No keywords defined.</li>
			@endforelse
		</ul>

		<h3>
			@auth
				@if ($database->user_id == Auth::id()) 
					<x-button method="GET" class="inline" action="{{ route('databases.edit', $database->id) }}">Edit</x-button>
				@endif
			@endauth
			Other Metadata:</h3>
		<ul class="list-disc list-inside">
				<!-- mandatory metadata --!>
			@if ($database->productionyear != null) <li><b>Production Year</b>: {{ $database->productionyear }}</li>@endif 
			@if ($database->publicationyear != null) <li><b>Publication Year</b>: {{ $database->publicationyear }}</li>@endif 
			@if ($database->resourcetype != null) <li><b>Resource Type ({{ \App\Models\Database::resourcetypeDisplay($database->resourcetype) }})</b>
				@if ($database->resource != null) : {{ $database->resource }}@endif 
			</li>@endif  
			@if ($database->controlledrights != null) <li><b>Rights:</b> {{ \App\Models\Database::controlledrightsDisplay($database->controlledrights) }}
				@if ($database->additionalrights != null) ({{ $database->additionalrights }})</li>@endif 
			</li>@endif 

				<!-- optional metadata --!>
			@if ($database->additionaltitletype != null) <li><b>Additional Title ({{ \App\Models\Database::additionaltitletypeDisplay($database->additionaltitletype) }})</b>
				@if ($database->additionaltitle != null) : {{ $database->additionaltitle }}@endif 
			</li>@endif  
			@if ($database->descriptiontype != null) <li><b>Description ({{ \App\Models\Database::descriptiontypeDisplay($database->descriptiontype) }})</b>
				@if ($database->description != null) : {{ $database->description }}@endif 
			</li>@endif  
			@if ($database->language != null) <li><b>Language</b>: {{ $database->language }}</li>@endif 
			@if ($database->datasources != null) <li><b>Datasoures</b>: {{ $database->datasources }}</li>@endif 
			@if ($database->software != null) <li><b>Software</b>: {{ $database->software }}</li>@endif 
			@if ($database->processing != null) <li><b>Processing</b>: {{ $database->processing }}</li>@endif 
			@if ($database->relatedinformation != null) <li><b>Related Information</b>: {{ $database->relatedinformation }}</li>@endif 
		</ul>

	<h2>Datasets:</h2>
		@can('create', [App\Models\Dataset::class, $database])
			@if(count($database->datasetdefs))
				<p>
				<x-button method="GET" class="inline" action="{{ route('databases.datasets.create', [$database->id]) }}">New</x-button>
				<x-button method="GET" class="inline" action="{{ route('databases.datasets.bulkupload', [$database->id]) }}">Bulk Upload</x-button>
				</p>
			@endif
		@endcan
		@if(count($database->datasets)==0)
			No datasets found.
		@else
			<b>{{ count($database->datasets) }}</b> datasets found:
			<ul class="list-disc list-inside">
			@foreach($database->datasets as $dataset)
				<li>
					@can('delete', $dataset)
						<x-button method="DELETE" class="inline" action="{{ route('datasets.destroy', [$dataset]) }}">Delete</x-button>
					@endcan
					<x-dataset.list link='true' :dataset="$dataset" />
				</li>
			@endforeach
			</ul>
		@endif

	<h2>Comments:</h2>
		@if(count($database->comments)==0)
			No comments found.
		@else
			<b>{{ count($database->comments) }}</b> comments found:
			<ul class="list-disc list-inside">
			@foreach($database->comments as $comment)
				<li>
					@auth
						@if ($comment->user_id == Auth::id()) 
							<x-button method="DELETE" class="inline" action="{{ route('comments.destroy', [$comment]) }}">Delete</x-button>
							<x-button method="GET" class="inline" action="{{ route('comments.edit', [$comment]) }}" >Edit</x-button>
						@endif
					@endauth
					<b> {{ $comment->user->name }} </b> wrote on {{ $comment->created_at }}: {{ $comment->text }}
				</li>
			@endforeach
			</ul>
			@auth
				<x-button method="GET" class="inline" action="{{ route('databases.comments', $database->id) }}">Comment</x-button>
			@endauth
		@endif


@env('local')
    <div class="text-xs">
        <p>Uploaded by: {{ $user->name }}<br>
        Created: {{ $database->created_at }}<br>
        Updated: {{ $database->updated_at }}</p>
    </div>
@endenv

</x-app-layout>
