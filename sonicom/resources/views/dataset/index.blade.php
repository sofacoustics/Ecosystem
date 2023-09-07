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
    <table class="table-auto">
        <tr class="text-left">
            <th>Title</th>
        </tr>
    @foreach ($allDatasets as $dataset)
        <tr>
            <td><a class="btn btn-primary" href="{{ route('dataset.show', $dataset->id) }}">{{ $dataset->title }}</a>
                @if ($dataset->uploader_id == Auth::id())
                (<a class="btn btn-primary" href="{{ route('dataset.edit', $dataset->id) }}">Edit</a>,&nbsp
                <form method="POST" id="delete-dataset" action="{{ route('dataset.destroy', $dataset->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="submit" class="btn btn-primary" value="Delete">
                </form>
                @endif
            </td>
        </tr>
    @endforeach
    </table>
</x-app-layout>