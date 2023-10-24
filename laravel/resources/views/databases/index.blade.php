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
    <table class="table-auto border border-slate-400">
        <tr class="text-left">
            <th>Title</th>
            <th>Description</th>
        </tr>
    @foreach ($allDatabases as $database)
        <tr class="border">
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a></td>
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->description}}</a></td>
            @if ($database->uploader_id == Auth::id())
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
</x-app-layout>