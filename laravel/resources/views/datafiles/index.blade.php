<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Files
        </h2>
    </x-slot>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p>The following datafiles are available</p>
    <table class="table-auto">
        <tr class="text-left">
            <th>Title</th>
        </tr>
    @foreach ($allDatafiles as $datafile)
        <tr>
            <td>{{ $datafile->name }}</td>
        </tr>
    @endforeach
    </table>
</x-app-layout>
