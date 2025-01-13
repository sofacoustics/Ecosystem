<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database: {{ $database->title }}
            @auth
                @if( Auth::user()->id  == $database->user_id)
                    (<a class="btn btn-primary" href="{{ route('databases.edit', $database->id) }}">Edit</a>)
                @endif
            @endauth
        </h2>
        User: {{ \App\Models\User::find($database->user_id)->name }}
        @auth
        @endauth<br>
        Description: {{ $database->description }}<br>
        <a class="btn btn-primary" href="{{ route("databases.radar", $database->id) }}">RADAR Metadata</a>
        @auth
            @if( Auth::user()->id  == $database->user_id)
                (<a class="btn btn-primary" href="{{ route('databases.radar.edit', $database->id) }}">Edit</a>)
            @endif
        @endauth
    </x-slot>
    <h3>Datasets</h3>

    @forelse($database->datasets as $dataset)
        - <a href={{ route('datasets.show', $dataset->id) }}>{{ $dataset->name }}</a> <br>
    @empty
        <p>There are no datasets associated with this database</p>
    @endforelse


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
