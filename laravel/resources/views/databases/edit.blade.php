<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Database
        </h2>
    </x-slot>
    <h1>Title: {{ $database->title }}</h1>
    {{ $database }}
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('update', $database)
    <form action="{{ route('databases.update', $database->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ $database->title }}">
        <input type="text" name="description" id="title" value="{{ $database->description }}">
        <input type="submit" name="submit" value="Submit">
    </form>
    @else
        <p>BUG: You may not edit this database! You should not be able to access this page. Please report this to the webmaster.</p>
    @endcan
    @guest
        <p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
    @endguest
</x-app-layout>