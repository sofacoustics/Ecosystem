<x-app-layout>
    <h1>Datasets</h1>

    <p>resources/views/dataset/index.blade.php</p>
    <h2>A list of all datasets in the SONICOM Ecosystem (not necessarily very useful!)</h2>
    @foreach($datasets as $dataset)
        <p>{{ $dataset->name }} (Database: <a href="{{ route('databases.show', $dataset->database->id) }}">{{ $dataset->database->title }}</a>)</p>
    @endforeach

</x-app-layout>
