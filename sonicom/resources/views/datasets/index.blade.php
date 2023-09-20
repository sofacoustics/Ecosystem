<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Datasets
        </h2>
    </x-slot>


    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <p>The following local datasets are available</p>
    <table class="table-auto border border-slate-400">
        <tr class="text-left">
            <th>Title</th>
            <th>Description</th>
        </tr>
    @foreach ($allDatasets as $dataset)
        <tr class="border">
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->title }}</a></td>
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->description}}</a></td>
            @if ($dataset->uploader_id == Auth::id())
            <td class="border p-2">
                (<a class="btn btn-primary" href="{{ route('datasets.edit', $dataset->id) }}">Edit</a>,&nbsp
                <form class="inline" method="POST" id="delete-dataset" action="{{ route('datasets.destroy', $dataset->id) }}">
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