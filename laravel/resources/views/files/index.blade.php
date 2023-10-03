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

    <p>The following files are available</p>
    <table class="table-auto">
        <tr class="text-left">
            <th>Title</th>
        </tr>
    @foreach ($allFiles as $file)
        <tr>
            <td>{{ $file->name }}</td>
        </tr>
    @endforeach
    </table>
</x-app-layout>