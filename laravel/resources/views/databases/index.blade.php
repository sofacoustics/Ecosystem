<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Databases
        </h2>
        @auth
            {{-- <x-button primary label="New" href="{{ route('databases.create') }}"/> --}}
                <a class="bg-green-100 inline" href="{{ url('/databases/create') }}">New</a>
        @endauth
    </x-slot>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <p>The SONICOM Ecosystem contains the following databases:</p>
    <table class="table-auto border border-slate-399">
        <tr class="text-left">
            <th class="border p-2">Name</th>
            <th class="border p-2">Description</th>
						<th class="border p-2">Commands</th>
        </tr>
    @foreach ($allDatabases as $database)
        <tr class="border">
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a></td>
            <td class="border p-2">{{ $database->description}}</td>
            <td class="border p-2">
							<a class="bg-green-100 inline" href="{{ route('databases.show', $database->id) }}">Show</a>&nbsp
							@if ($database->user_id == Auth::id())
								<a class="bg-green-100 inline" href="{{ route('databases.edit', $database->id) }}">Edit Metadata</a>&nbsp
                <form class="bg-red-100 inline" method="POST" action="{{ route('databases.destroy', [$database]) }}">
									@csrf @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
            @endif
        </tr>
    @endforeach
		</table>


{{-- START: Testing RADAR dataset --}}
@env('ignore')
    {{-- in "local" or "staging" environment --}}

		@foreach ($allDatabases as $database)
			<div>Database: {{ $database->title }}
             <p>RADAR dataset<p>
                     <p>Creator: {{ $database->radardataset->descriptiveMetadata->creators->creator[0]['creatorName'] }}</p>
            @foreach ($database->radardataset->descriptiveMetadata->creators->creator as $creator)
                <p>creatorName: {{ $creator['creatorName'] }}</p>
                {{-- @if(!is_null($creator['creatorAffiliation'])) --}}
                @if(array_key_exists('creatorAffiliation', $creator) && !is_null($creator['creatorAffiliation']))
                    <p>creatorAffiliation: {{ $creator['creatorAffiliation']['value'] }}</p>
                @else
                    <p>No affiliation</pj
                @endif
            @endforeach
            Subject Areas:
            @foreach ($database->radardataset->descriptiveMetadata->subjectAreas->subjectArea as $subjectArea)
                <p>controlledSubjectAreaName: {{ $subjectArea['controlledSubjectAreaName'] }}</p>
                @if(!empty($subjectArea['additionalSubjectAreaName']))
                    <p>additionalSubjectAreaName: {{ $subjectArea['additionalSubjectAreaName']}}</p>
                @endif
            @endforeach
            Resource: {{ $database->radardataset->descriptiveMetadata->resource->value }}

            @php
                //var_dump($database->radardataset->creators->creator[0]);
                //    var_dump($database->radardataset['publishers']);
            @endphp
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
            <p>Pure JSON output of JSON field 'radardataset' using the RadardatsetpureData laravel-data class</p>
             <pre>{{ $database->radardataset->toJson(JSON_PRETTY_PRINT) }}</pre>

        @endforeach
{{-- END: Testing RADAR dataset --}}
@endenv

{{-- <livewire:create-database /> --}}
</x-app-layout>
