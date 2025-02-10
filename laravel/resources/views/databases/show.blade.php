<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
		<h3>Creators:
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
					<a class="bg-green-100 inline" href="{{ route('databases.creators', $database->id) }}">Edit</a>
        @endif
      @endauth
		</h3>
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

		<h3>Publishers:
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
					<a class="bg-green-100 inline" href="{{ route('databases.publishers', $database->id) }}">Edit</a>
        @endif
      @endauth
		</h3>
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

		<h3>Subject Areas:
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
					<a class="bg-green-100 inline" href="{{ route('databases.subjectareas', $database->id) }}">Edit</a>
        @endif
      @endauth
		</h3>
		<ul class="list-disc list-inside">
		@forelse ($database->subjectareas as $subjectarea)
		<li><b>{{ \App\Models\Database::subjectareaDisplay($subjectarea->controlledSubjectAreaIndex) }}</b>
				@if ($subjectarea->additionalSubjectArea != null) ({{ $subjectarea->additionalSubjectArea }}) @endif
		</li>
		@empty
			<li>No subject areas defined.</li>
		@endforelse
		</ul>

		<h3>Rights Holders:
			@auth
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}
					<a class="bg-green-100 inline" href="{{ route('databases.rightsholders', $database->id) }}">Edit</a>
        @endif
      @endauth
		</h3>
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
			<li>No rights holder defined.</li>
		@endforelse
		</ul>

		<h3>Metadata:
			@auth
        @if ($database->user_id == Auth::id())  {{-- If we own this database --}}  
					<a class="bg-green-100 inline" href="{{ route('databases.edit', $database->id) }}">Edit</a>
        @endif
			@endauth
		</h3>
		<ul class="list-disc list-inside">
			{{-- mandatory metadata --}}
		@if ($database->productionyear != null) <li><b>Production Year</b>: {{ $database->productionyear }}</li>@endif 
		@if ($database->publicationyear != null) <li><b>Publication Year</b>: {{ $database->publicationyear }}</li>@endif 
		@if ($database->resourcetype != null) <li><b>Resource Type ({{ \App\Models\Database::resourcetypeDisplay($database->resourcetype) }})</b>
			@if ($database->resource != null) : {{ $database->resource }}@endif 
		</li>@endif  
		@if ($database->controlledrights != null) <li><b>Rights:</b> {{ \App\Models\Database::controlledrightsDisplay($database->controlledrights) }}
			@if ($database->additionalrights != null) ({{ $database->additionalrights }})</li>@endif 
		</li>@endif 

			{{-- optional metadata --}}
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

    <h3>Definition of a Dataset:
			@auth            
				@if ($database->user_id == Auth::id())  {{-- If we own this database --}}                
					@if(count($database->datasets)==0)  {{-- If there's a dataset, then we cannot change the datasetdef anymore --}}
						<a class="bg-green-100 inline" href="{{ route('databases.datasetdefs', $database->id) }}">Edit</a>
          @endif
        @endif
      @endauth
    </h3>
			<ul class="list-disc list-inside">
			@forelse($database->datasetdefs as $datasetdef)
				<li><x-datasetdef.list link='true' :datasetdef="$datasetdef"/></li>
			@empty
				<li>There is no definition of a dataset yet.</li>
			@endforelse
			</ul>

    <h3>Datasets:
        @can('create', [App\Models\Dataset::class, $database])
            {{-- jw:todo move to policy If there's a datasetdef, then we can create a dataset --}}
            @if(count($database->datasetdefs))
                {{-- <a href="{{ route('databases.datasets.create', $database->id) }}">(New)</a><br> --}}
                <x-button
                    method="GET"
                    action="{{ route('databases.datasets.create', [$database->id]) }}"
                    class="inline">
                    New
                </x-button>
            @endif
        @endcan
    </h3>
    <ul class="list-disc list-inside">
    @forelse($database->datasets as $dataset)
        <li>
            <x-dataset.list link='true' :dataset="$dataset" />
            @can('delete', $dataset)
                <x-button
                    method="DELETE"
                    action="{{ route('datasets.destroy', [$dataset]) }}"
                    class="inline">
                    DELETE
                </x-button>
            @endcan
        </li>
    @empty
        <li>There are no datasets associated with this database</li>
    @endforelse
    </ul>

@env('local')
    <div class="text-xs">
        <p>Uploaded by: {{ $user->name }}<br>
        Created: {{ $database->created_at }}<br>
        Updated: {{ $database->updated_at }}</p>
    </div>
@endenv

</x-app-layout>
