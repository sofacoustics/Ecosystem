<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Databases
        </h2>
    </x-slot>


    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <p>The following local databases are available</p>
    <table class="table-auto border border-slate-399">
        <tr class="text-left">
            <th>Name</th>
            <th>Description</th>
        </tr>
    @foreach ($allDatabases as $database)
        <tr class="border">
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->name }}</a></td>
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->description}}</a></td>
            @if ($database->user_id == Auth::id())
            <td class="border p-2">
                (<a class="btn btn-primary" href="{{ route('databases.edit', $database->id) }}">Edit</a>,&nbsp
                <form class="inline" method="POST" id="delete-database" action="{{ route('databases.destroy', $database->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-primary" value="Delete">
                </form>)
            </td>
            @endif
        </tr>
    @endforeach
		</table>
{{-- 		//jw:tmp testing relationships<br>
		
		@foreach ($allDatabases as $database)
			Database: {{ $database->name }} <br>
            @foreach ($database->datasets as $dataset)
                - Dataset: $dataset <br>
                @foreach ($dataset->datafiles as $datafile)
                - - Datafile: {{ $datafile }}<br>
                - - - {{ $datafile->dataset->database->id }} absolutepath = {{ $datafile->absolutepath() }}<br>
                @endforeach

            @endforeach

        @endforeach
--}}

{{-- START: Testing RADAR dataset --}}
		@foreach ($allDatabases as $database)
			<div>Database: {{ $database->name }}
            <ul class="list-disc list-inside">
            <li>RADAR Dataset: {{ $database->radardataset }}</li>
            <li>RADAR Dataset Resource: {{ $database->radardataset->radardatasetresourcetype }}</li>
            <li>RADAR Dataset Subject Area: {{ $database->radardataset->radardatasetsubjectarea }}</li>
            <li>RADAR Dataset Subject Areas:
                <ul class="list-disc list-inside">
                @foreach ($database->radardataset->radardatasetsubjectareas as $radardatasetsubjectarea)
                    <li>{{ $radardatasetsubjectarea  }}</li>
                @endforeach
                </ul>
            </ul>
            JSON: {{ $database->radardataset->json() }}
            </div>
        @endforeach
{{-- END: Testing RADAR dataset --}}

<livewire:create-database />
</x-app-layout>
