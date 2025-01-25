<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
		<h3>Creators</h3>
{{--	// product --> single category
			// creator --> single database --}}
		@foreach ($database->creators as $creator)
    <p><b>Name</b>: {{ $creator->creatorName }}
		  @if ($creator->givenName != null), <b>Given Name</b>: {{ $creator->givenName }} @endif 
			@if ($creator->givenName != null), <b>Family Name</b>: {{ $creator->familyName }} @endif
			@if ($creator->nameIdentifier != null), <b>{{ $creator->nameIdentifierScheme }}</b>: {{ $creator->nameIdentifier }} @endif
			@if ($creator->creatorAffiliation != null), <b>Affiliation</b>: {{ $creator->creatorAffiliation }} @endif
			@if ($creator->affiliationIdentifier != null), <b>{{ $creator->affiliationIdentifierScheme }}</b>: {{ $creator->affiliationIdentifier }} @endif
		</p> 
		@endforeach
		
    <h3>Datasets
        @auth
            {{-- If we own this database --}}
            @if ($database->user_id == Auth::id())
                {{-- If there's a datasetdef, then we can create a dataset --}}
                @if(count($database->datasetdefs))
                    <a href="{{ route('databases.datasets.create', $database->id) }}">(New)</a><br>
                @endif
            @endif
        @endauth
    </h3>

    <ul class="list-disc list-inside">
    @forelse($database->datasets as $dataset)
        <li><x-dataset.list link='true' :dataset="$dataset"/></li>
    @empty
        <p>There are no datasets associated with this database</p>
    @endforelse
    </ul>

@env('local')
    {{-- in "local" or "staging" environment --}}
    {{--<h3>RADAR dataset</h3>--}}

    {{--
    <form method="post" action="http://sonicom-jw-local.local/api/radar/datasets/f3dxp4vswdc2xe7z">
            <input class="rounded-full bg-amber-200 p-2" type="submit" value="send">
    </form> --}}

{{--    <x-button method="post" action="http://sonicom-jw-local.local/api/radar/datasets/{{ $database->id }}">
        Upload metadata to RADAR
    </x-button> --}}

    {{--
    @auth
        <livewire:radar.dataset :database="$database" />
    @else
        <x-radar.dataset :dataset="$database->radardataset">
            A radar.div component with a dataset parameter
        </x-radar.dataset>
    @endauth
    --}}


    @php
        //var_dump($database->radardataset->creators->creator[0]);
        //    var_dump($database->radardataset['publishers']);
    @endphp
    {{--
    <p>Pure JSON output of JSON field 'radardataset' using the RadardatsetpureData laravel-data class</p>
     <pre>{{ $database->radardataset->toJson(JSON_PRETTY_PRINT) }}</pre>
     --}}
    <ul class="list-disc list-inside">
    {{--
        <li>RADAR Dataset: {{ $database->radardataset }}</li>
        <li>RADAR Dataset Resource: {{ $database->radardataset->radardatasetresourcetype }}</li>
        <li>RADAR Dataset Rights Holders: {{ $database->radardataset->radardatasetrightsholders }}</li>
        <li>RADAR Dataset Subject Area: {{ $database->radardataset->radardatasetsubjectarea }}</li>
        <li>RADAR Dataset Subject Areas:
            <ul class="list-disc list-inside">
            @foreach ($database->radardataset->radardatasetsubjectareas as $radardatasetsubjectarea)
                <li>{{ $radardatasetsubjectarea  }}</li>
            @endforeach
            </ul>
        <li>RADAR Dataset Publishers: {{-- $database->radardataset->publishers->toJson() </li>
        <p>Publishers</p>
        @php
            //var_dump($database->radardataset->publishers->toArray());
            //var_dump(json_decode($database->radardataset->publishers->toJson()));
        @endphp
        Radardatset Model->toJson()
        <pre> $database->radardataset->toJson(JSON_PRETTY_PRINT) </pre> --}k
    </u>
    --}}
    {{-- JSON: {{ $database->radardataset->json() }} --}}
    </div>
{{-- END: Testing RADAR dataset --}}
@endenv


    <div class="text-xs">
    <p>Uploaded by: {{ $user->name }}<br>
    Created: {{ $database->created_at }}<br>
    Updated: {{ $database->updated_at }}</p>
    {{-- $database --}}
</div>
</x-app-layout>
