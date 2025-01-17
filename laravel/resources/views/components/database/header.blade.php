{{-- the database header to put in the app layout slot 'header' --}}

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database: <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
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
        <a class="btn btn-primary" href="{{ route("databases.show", $database->id) }}">Datasets</a>
        <a class="btn btn-primary" href="{{ route("databases.radar", $database->id) }}">RADAR Metadata</a>
        @auth
            @if( Auth::user()->id  == $database->user_id)
                (<a class="btn btn-primary" href="{{ route('databases.radar.edit', $database->id) }}">Edit</a>)
            @endif
        @endauth
        <a class="btn btn-primary" href="{{ route("databases.datasetdefs", $database->id) }}">Datasetdef</a>

