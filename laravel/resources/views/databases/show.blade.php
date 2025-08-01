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
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.creators', $database->id) }}">Add Creators</x-button></small>
				</h3>
			@endif
		@else
			<h3>Creators:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->creators as $creator)
			<li><x-creator.list :creator=$creator/></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.publishers', $database->id) }}">Add Publishers</x-button></small>
				</h3>
			@endif
		@else
			<h3>Publishers:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->publishers as $publisher)
			<li><x-publisher.list :publisher=$publisher/></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.rightsholders', $database->id) }}">Add Rightholders</x-button></small>
				<h3>
			@endif
		@else
			<h3>Rightsholders:</h3>			
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->rightsholders as $rightsholder)
			<li><x-rightsholder.list :rightsholder=$rightsholder /></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.keywords', $database->id) }}">Add Keywords</x-button></small>
				</h3>
			@endif
		@else
			<h3>Keywords:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->keywords as $keyword)
			<li><x-keyword.list :keyword=$keyword /></li>
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
				<h3><small><x-button method="GET" class="inline" action="{{ route('databases.relatedidentifiers', $database->id) }}">Add Relations</x-button></small>
				</h3>
			@endif
		@else
			<h3>Relations:</h3>
		@endcan
		<ul class="list-disc list-inside">
			@forelse ($database->relatedidentifiers as $relatedidentifier)
			<li>
				<x-relatedidentifier.list :relatedidentifier=$relatedidentifier />
			</li> 
			@empty
				@cannot('update', $database)
					<li>No relations defined.</li>
				@endcan
			@endforelse
		</ul>

		<div class="max-w-prose">
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
					@elseif($database->radar_status==4)
						<li><b>DOI (persistently published)</b>: <a href="https://doi.org/{{ $database->doi }}">{{ $database->doi }}</a>
						@if ($database->publicationyear != null) 
							<li><b>DOI Publication Year</b>: {{ $database->publicationyear }}</li>
						@endif 
					@endif
				@else
					<li><b>DOI</b>: not assigned yet
				@endif
				<li><b>Uploaded by:</b> {{ $user->name }}
					<a href="{{ \App\Models\Radar::schemeURI(1).$user->orcid }}">
					<img id="orcid" src="{{ asset('images/orcid_16x16.webp') }}"
						alt="ORCID: {{ $user->orcid }}" 
						title="{{ $user->orcid }}" 
						style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 1em; min-width: 1em;"></a>
					<a href="mailto:{{ $user->email }}">
					<img id="orcid" src="{{ asset('images/envelope.png') }}"
						alt="Email address: {{ $user->email }}" 
						title="{{ $user->email }}" 
						style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 1.5em; min-width: 1.5em;"></a>
				</li>
				
				<li><b>Date (created):</b> {{ $database->created_at }} (GMT)</li>

				<li><b>Date (updated):</b> {{ $database->updated_at }} (GMT)</li>

				@if ($database->productionyear != null) <li><b>Production Year</b>: {{ $database->productionyear }}</li>@endif
				
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

				@if ($database->datasources != null) <li><b>Data Source</b>: {{ $database->datasources }}</li>@endif 
				
				@if ($database->software != null) <li><b>Software</b>: {{ $database->software }}</li>@endif 
				
				@if ($database->processing != null) <li><b>Processing</b>: {{ $database->processing }}</li>@endif 
				
				@if ($database->relatedinformation != null) <li><b>Related Information</b>: {{ $database->relatedinformation }}</li>@endif 
			</ul>
		</div>

	<hr>
	
	<h2>Comments</h2>
		@if(count($database->comments)==0)
			<p>No comments found.</p>
		@else
			<b>{{ count($database->comments) }}</b> comments found:
			<ul class="list-disc list-inside">
			@foreach($database->comments as $comment)
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
		@can('create', \App\Models\Database::class)
			<x-button method="GET" class="inline" action="{{ route('databases.comments', $database->id) }}">New Comment</x-button>
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
