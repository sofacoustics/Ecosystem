{{--

Parameters:

	database
	user

--}}
<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database/>
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
			@if(count($database->keywords)>0)
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


		@can('update', $database)
			@if(count($database->relatedidentifiers)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.relatedidentifiers', $database->id) }}">Edit</x-button></small>
				Relations:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.relatedidentifiers', $database->id) }}">Add relations</x-button></small>
				</h3>
			@endif
		@else
			<h3>Relations:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->relatedidentifiers as $relatedidentifier)
			<li>
				<b>{{ \App\Models\Database::relationDisplay($relatedidentifier->relationtype) }}:</b> 
				@if (\App\Models\Database::relatedidentifierDisplay($relatedidentifier->relatedidentifiertype) === "URL")
					<a href="{{ $relatedidentifier->name }} ">{{ $relatedidentifier->name }}</a> 
				@else
					{{ $relatedidentifier->name }} 
				@endif
				({{ \App\Models\Database::relatedidentifierDisplay($relatedidentifier->relatedidentifiertype) }}).
			</li>
			@empty
				@cannot('update', $database)
					<li>No relations defined.</li>
				@endcan
			@endforelse
		</ul>


		<h3>
			@can('update', $database)
				<small><x-button method="GET" class="inline" action="{{ route('databases.edit', $database->id) }}">Edit</x-button></small>
			@endcan
			Other:
		</h3>
		<ul class="list-disc list-inside">
			@if ($database->doi != null) 
				@if($database->radar_status==1)
					<li><b>DOI (assigned)</b>: {{ $database->doi }}
				@elseif($database->radar_status==2)
					<li><b>DOI (publication requested)</b>: {{ $database->doi }}
				@elseif($database->radar_status==3)
					<li><b>DOI (persistently published)</b>: <a href="https://doi.org/{{ $database->doi }}">{{ $database->doi }}</a>
				@endif
			@else
				<li><b>DOI</b>: not assigned yet
			@endif
			<li><b>Uploaded by:</b> {{ $user->name }}</li>
			
			<li><b>Date created:</b> {{ $database->created_at }}</li>

			<li><b>Date updated:</b> {{ $database->updated_at }}</li>

			@if ($database->productionyear != null) <li><b>Production Year</b>: {{ $database->productionyear }}</li>@endif
			
			@if ($database->publicationyear != null) <li><b>Publication Year</b>: {{ $database->publicationyear }}</li>@endif 
			
			<li><b>Resource Type</b>: {{ \App\Models\Database::resourcetypeDisplay($database->resourcetype) }}
				@if ($database->resource != null) ({{ $database->resource }})@endif 
			</li>

			@if ($database->controlledrights != null) 
				<li><b>Rights:</b> {{ \App\Models\Metadataschema::display($database->controlledrights) }}
					@if ($database->additionalrights != null) ({{ $database->additionalrights }})@endif 
				</li>
			@endif 

			<li><b>Subject Areas</b>:
				@foreach ($database->subjectareas as $index => $subjectarea)@if($index>0),@endif
					{{ \App\Models\Database::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}@if ($subjectarea->additionalSubjectArea != null) {{ $subjectarea->additionalSubjectArea }}@endif
@endforeach <!-- do not change this line -->
			</li>

			@if ($database->descriptiongeneral != null)
				<li><b>General Description</b>: {{ $database->descriptiongeneral }}</li>
			@endif 
			
			@if ($database->descriptionabstract != null)
				<li><b>Abstract</b>: {{ $database->descriptionabstract }}</li>
			@endif 
			
			@if ($database->descriptionmethods != null)
				<li><b>Methods</b>: {{ $database->descriptionmethods }}</li>
			@endif 
			
			@if ($database->descriptionremarks != null)
				<li><b>Technical Remarks</b>: {{ $database->descriptionremarks }}</li>
			@endif 

			@if ($database->language != null) <li><b>Language</b>: {{ $database->language }}</li>@endif 
			
			@if ($database->datasources != null) <li><b>Datasoures</b>: {{ $database->datasources }}</li>@endif 
			
			@if ($database->software != null) <li><b>Software</b>: {{ $database->software }}</li>@endif 
			
			@if ($database->processing != null) <li><b>Processing</b>: {{ $database->processing }}</li>@endif 
			
			@if ($database->relatedinformation != null) <li><b>Related Information</b>: {{ $database->relatedinformation }}</li>@endif 
		</ul>

	<hr>
	
	<h2>Comments</h2>
		@if(count($database->comments)==0)
			<p>No comments found.</p>
		@else
			<b>{{ count($database->comments) }}</b> comments found:
			<ul class="list-disc list-inside">
			@foreach($database->comments as $comment)
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
			<x-button method="GET" class="inline" action="{{ route('databases.comments', $database->id) }}">Add a comment</x-button>
		@endcan

	<hr>

<div class="text-xs">
	<p>
		Uploaded by: {{ $user->name }}<br>
		Created: {{ $database->created_at }}<br>
		Updated: {{ $database->updated_at }}
	</p>
</div>

</x-app-layout>
