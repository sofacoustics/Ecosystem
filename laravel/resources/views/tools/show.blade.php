{{--

Parameters:

	tool
	user

--}}
<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool=$tool />
	</x-slot>
	<h2>The File</h2>
		@if($tool->filesize())
			<li><b>File Name:</b> {{ $tool->filename }}
			<li>
				<b>Download Link:</b> <a href="{{ asset($tool->url()) }}" download>{{ $tool->filename }}</a>
				<img id="copyButton" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 2em; display: inline-block;">
				<input type="text" id="textToCopy" value="{{ asset($tool->url()) }}" class="hidden"><br>
			<li><b>File Size:</b> {{ $tool->filesize() }} bytes 
				@if($tool->filesize() > 10240)
				= {{ round($tool->filesize() / 1024, 2) }} kbytes 
					@if($tool->filesize() > (1024*10240))
						= {{ round($tool->filesize() / 1024 / 1024, 2)  }} MB 
						@if($tool->filesize() > (1024*102410240))
							= {{ round($tool->filesize() / 1024 / 1024 / 1024, 2) }} GB
						@endif
					@endif
				@endif
		@else
			<li><b>File Name:</b> File not provided yet, upload a file</li>
		@endif

	<hr>
	
	<h2>Metadata</h2>
		@can('update', $tool)
			@if(count($tool->creators)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.creators', $tool->id) }}">Edit</x-button></small>
					Creators:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.creators', $tool->id) }}">Add Creators</x-button></small>
				</h3>
			@endif
		@else
			<h3>Creators:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->creators as $creator)
			<li><x-creator.list :creator=$creator/></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.publishers', $tool->id) }}">Add Publishers</x-button></small>
				</h3>
			@endif
		@else
			<h3>Publishers:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->publishers as $publisher)
			<li><x-publisher.list :publisher=$publisher/></li>
			@empty
				@cannot('update', $tool)
					<li>No publishers defined.</li>
				@endcan
			@endforelse
		</ul>

		@can('update', $tool)
			@if(count($tool->rightsholders)>0)
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.rightsholders', $tool->id) }}">Edit</x-button></small>
				Rightsholders:</h3>
			@else
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.rightsholders', $tool->id) }}">Add Rightholders</x-button></small>
				<h3>
			@endif
		@else
			<h3>Rightsholders:</h3>			
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->rightsholders as $rightsholder)
			<li><x-rightsholder.list :rightsholder=$rightsholder /></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.keywords', $tool->id) }}">Add Keywords</x-button></small>
				</h3>
			@endif
		@else
			<h3>Keywords:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->keywords as $keyword)
			<li><x-keyword.list :keyword=$keyword /></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('tools.relatedidentifiers', $tool->id) }}">Add Relations</x-button></small>
				</h3>
			@endif
		@else
			<h3>Relations:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($tool->relatedidentifiers as $relatedidentifier)
			<li>
				<x-relatedidentifier.list :relatedidentifier=$relatedidentifier />
			</li> 
			@empty
				@cannot('update', $tool)
					<li>No relations defined.</li>
				@endcan
			@endforelse
		</ul>

		<div class="max-w-prose">
			<h3>
				@can('update', $tool)
					<small><x-button method="GET" class="inline" action="{{ route('tools.edit', $tool->id) }}">Edit</x-button></small>
				@endcan
				Other:
			</h3>
			<ul class="list-disc list-inside">
				@if ($tool->doi != null) 
					@if($tool->radar_status==1)
						<li><b>DOI (assigned)</b>: {{ $tool->doi }}
					@elseif($tool->radar_status==2)
						<li><b>DOI (publication requested)</b>: {{ $tool->doi }}
					@elseif($tool->radar_status==4)
						<li><b>DOI (persistently published)</b>: <a href="https://doi.org/{{ $tool->doi }}">{{ $tool->doi }}</a>
						@if ($tool->publicationyear != null) 
							<li><b>Publication Year</b>: {{ $tool->publicationyear }}</li>
						@endif 
					@endif
				@else
					<li><b>DOI</b>: not assigned yet
				@endif
				
				<li><b>Uploaded by:</b> {{ $user->name }}</li>
				
				<li><b>Date (created):</b> {{ $tool->created_at }} (GMT)</li>
				
				<li><b>Date (updated):</b> {{ $tool->updated_at }} (GMT)</li>
				
				@if ($tool->productionyear != null) <li><b>Production Year</b>: {{ $tool->productionyear }}</li>@endif
				
				@if ($tool->resourcetype != null) <li><b>Resource Type</b>: {{ \App\Models\Tool::resourcetypeDisplay($tool->resourcetype) }}</b>
					@if ($tool->resource != null) ({{ $tool->resource }})@endif 
				</li>@endif  
				
				@if ($tool->controlledrights != null) <li><b>Rights:</b> {{ \App\Models\Metadataschema::display($tool->controlledrights) }}
					@if ($tool->additionalrights != null) ({{ $tool->additionalrights }})</li>@endif 
				</li>@endif 
				
				<li><b>Subject Areas</b>:
					@foreach ($tool->subjectareas as $index => $subjectarea)@if($index>0),@endif
						{{ \App\Models\Tool::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}@if ($subjectarea->additionalSubjectArea != null) {{ $subjectarea->additionalSubjectArea }}@endif
	@endforeach <!-- do not change this line --> 
				</li>

				@if ($tool->descriptiongeneral != null)
					<li><b>General Description</b>: {{ $tool->descriptiongeneral }}</li>
				@endif 
				
				@if ($tool->descriptionabstract != null)
					<li><b>Abstract</b>: {{ $tool->descriptionabstract }}</li>
				@endif 
				
				@if ($tool->descriptionmethods != null)
					<li><b>Methods</b>: {{ $tool->descriptionmethods }}</li>
				@endif 
				
				@if ($tool->descriptionremarks != null)
					<li><b>Technical Remarks</b>: {{ $tool->descriptionremarks }}</li>
				@endif 
			</ul>
		</div>

	<hr>
	
	<h2>Comments</h2>
		@if(count($tool->comments)==0)
			<p>No comments found.</p>
		@else
			<b>{{ count($tool->comments) }}</b> comments found:
			<ul class="list-disc list-inside">
			@foreach($tool->comments as $comment)
				<li>
					@can('update',$comment)
							<x-button method="DELETE" class="inline" action="{{ route('comments.destroy', $comment) }}">Delete</x-button>
							<x-button method="GET" class="inline" action="{{ route('comments.edit', $comment) }}" >Edit</x-button>
					@endcan
					<b> {{ $comment->user->name }} </b><small>({{ $comment->created_at }})</small>: {{ $comment->text }}
				</li>
			@endforeach
			</ul>
		@endif
		@can('create', \App\Models\Tool::class)
			<x-button method="GET" class="inline" action="{{ route('tools.comments', $tool->id) }}">New Comment</x-button>
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
