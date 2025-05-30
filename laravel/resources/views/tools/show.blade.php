{{--

Parameters:

	tool
	user

--}}
<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool=$tool />
	</x-slot>

	<h2>Metadata</h2>
		@can('update', $tool)
			@if(count($tool->creators)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.creators', $tool->id) }}">Edit</x-button></small>
					Creators:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.creators', $tool->id) }}">Add creators</x-button></small>
				</h3>
			@endif
		@else
			<h3>Creators:</h3>
		@endcan

		<ul class="list-disc list-inside">
			@forelse ($tool->creators as $creator)
			<li><b>Name</b>: {{ $creator->creatorName }}
				@if ($creator->givenName != null) <b>Given Name</b>: {{ $creator->givenName }}@endif 
				@if($creator->familyName != null) <b>Family Name</b>: {{ $creator->familyName }}@endif
				@if ($creator->nameIdentifier != null) <b>{{ $creator->nameIdentifierScheme($creator->nameIdentifierSchemeIndex) }}</b>: {{ $creator->nameIdentifier }}@endif
				@if ($creator->creatorAffiliation != null) <b>Affiliation</b>: {{ $creator->creatorAffiliation }}@endif
				@if ($creator->affiliationIdentifier != null) <b>{{ $creator->affiliationIdentifierScheme }}</b>: {{ $creator->affiliationIdentifier }}@endif 
			</li>
			@empty
				@cannot('update', $tool)
					<li>No creators defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $tool)
			@if(count($tool->publishers)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.publishers', $tool->id) }}">Edit</x-button></small>
					Publishers:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.publishers', $tool->id) }}">Add publishers</x-button></small>
				</h3>
			@endif
		@else
			<h3>Publishers:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->publishers as $publisher)
			<li><b>Name</b>: {{ $publisher->publisherName }}
				@if ($publisher->nameIdentifier != null) <b>{{ $publisher->nameIdentifierScheme($publisher->nameIdentifierSchemeIndex) }}</b>: 
					@if ($publisher->schemeURI != null) <a href="{{ $publisher->schemeURI }}"> @endif
					{{ $publisher->nameIdentifier }}
					@if ($publisher->schemeURI != null) </a> @endif
				@endif
			</li>
			@empty
				@cannot('update', $tool)
					<li>No publishers defined.</li>
				@endcan
			@endforelse
		</ul>

		<h3>Subject Areas:</h3>
		<ul class="list-disc list-inside">
			@forelse ($tool->subjectareas as $subjectarea)
			<li><b>{{ \App\Models\Tool::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}</b>
					@if ($subjectarea->additionalSubjectArea != null) ({{ $subjectarea->additionalSubjectArea }}) @endif
			</li>
			@empty
				@cannot('update', $tool)
					<li>No subject areas defined.</li>
				@endcan
			@endforelse
		</ul> 

		@can('update', $tool)
			@if(count($tool->rightsholders)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.rightsholders', $tool->id) }}">Edit</x-button></small>
				Rightsholders:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.rightsholders', $tool->id) }}">Add rightholders</x-button></small>
				<h3>
			@endif
		@else
			<h3>Rightsholders:</h3>			
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->rightsholders as $rightsholder)
			<li><b>Name</b>: {{ $rightsholder->rightsholderName }}
				@if ($rightsholder->nameIdentifier != null) <b>{{ $rightsholder->nameIdentifierScheme($rightsholder->nameIdentifierSchemeIndex) }}</b>: 
					@if ($rightsholder->schemeURI != null) <a href="{{ $rightsholder->schemeURI }}"> @endif
					{{ $rightsholder->nameIdentifier }}
					@if ($rightsholder->schemeURI != null) </a> @endif
				@endif
			</li>
			@empty
				@cannot('update', $tool)
					<li>No rightsholder defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $tool)
			@if(count($tool->rightsholders)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.keywords', $tool->id) }}">Edit</x-button></small>
				Keywords:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.keywords', $tool->id) }}">Add keywords</x-button></small>
				</h3>
			@endif
		@else
			<h3>Keywords:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->keywords as $keyword)
			<li>
				<b>{{ $keyword->keywordName }}</b> 
				@if($keyword->classificationCode)
					(@if ($keyword->schemeURI != null)<a href="{{ $keyword->schemeURI }}">@endif{{ \App\Models\Keyword::keywordScheme($keyword->keywordSchemeIndex)}}@if ($keyword->schemeURI != null)</a>@endif: 
					@if ($keyword->valueURI != null)<a href="{{ $keyword->valueURI }}">@endif{{ $keyword->classificationCode }}@if ($keyword->valueURI != null)</a>@endif)
				@endif
			</li>
			@empty
				@cannot('update', $tool)
					<li>No keywords defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $tool)
			@if(count($tool->relatedidentifiers)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.relatedidentifiers', $tool->id) }}">Edit</x-button></small>
				Relations:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.relatedidentifiers', $tool->id) }}">Add relations</x-button></small>
				</h3>
			@endif
		@else
			<h3>Relations:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->relatedidentifiers as $relatedidentifier)
			<li>
				<b>{{ \App\Models\Tool::relationDisplay($relatedidentifier->relationtype) }}:</b> 
				{{ $relatedidentifier->name }} 
				({{ \App\Models\Tool::relatedidentifierDisplay($relatedidentifier->relatedidentifiertype) }}).
			</li>
			@empty
				@cannot('update', $tool)
					<li>No relations defined.</li>
				@endcan
			@endforelse
		</ul>



	<h2>Other Metadata</h2>
		@if($tool->filesize())
			<p>
				<b>File Name:</b> <a href="{{ asset($tool->url()) }}" download>{{ $tool->filename }}</a>
				<img id="copyButton" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 2em; display: inline-block;">
				<input type="text" id="textToCopy" value="{{ asset($tool->url()) }}" class="hidden">
			</p>
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
		<p>Date created: {{ $tool->created_at }}</p>
		<p>Date updated: {{ $tool->updated_at }}</p>
		
		@if ($tool->productionyear != null) <li><b>Production Year</b>: {{ $tool->productionyear }}</li>@endif
		
		@if ($tool->publicationyear != null) <li><b>Publication Year</b>: {{ $tool->publicationyear }}</li>@endif 
		
		@if ($tool->resourcetype != null) <li><b>Resource Type</b>: {{ \App\Models\Tool::resourcetypeDisplay($tool->resourcetype) }}</b>
			@if ($tool->resource != null) ({{ $tool->resource }})@endif 
		</li>@endif  
		
		@if ($tool->controlledrights != null) <li><b>Rights:</b> {{ \App\Models\Tool::controlledrightsDisplay($tool->controlledrights) }}
			@if ($tool->additionalrights != null) ({{ $tool->additionalrights }})</li>@endif 
		</li>@endif 
		
		@if ($tool->descriptiongeneral != null)
			<li><b>General Description</b>: {{ $tool->descriptiongeneral }}</li>
		@endif 

		
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
		@can('create', \App\Models\Tool::class)
			<x-button method="GET" class="inline" action="{{ route('tools.comments', $tool->id) }}">Add a comment</x-button>
		@endcan

<script>
document.getElementById('copyButton').addEventListener('click', function() {
		// Get the text from the input field
		var textToCopy = document.getElementById('textToCopy').value;

		// Use the Clipboard API to copy the text
		navigator.clipboard.writeText(textToCopy).then(function() {
				alert(textToCopy + '\ncopied to the clipboard...');
		}).catch(function(err) {
				console.error('Failed to copy text: ', err);
				alert('Failed to copy text. Please copy manually.'); // Inform the user
		});
});
</script>


<div class="text-xs">
	<p>
		Uploaded by: {{ $user->name }}<br>
		Created: {{ $tool->created_at }}<br>
		Updated: {{ $tool->updated_at }}
	</p>
</div>

</x-app-layout>
