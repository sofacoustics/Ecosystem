<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Dataset
        </h2>
    </x-slot>
    <h1>Title: {{ $dataset->title }}</h1>
    {{ $dataset }}
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('update', $dataset)
    <form action="{{ route('dataset.update', $dataset->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="{{ $dataset->title }}">
        <input type="submit" name="submit" value="Submit">
    </form>
    @else
        <p>BUG: You may not edit this dataset! You should not be able to access this page. Please report this to the webmaster.</p>
    @endcan
    @guest
        <p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
    @endguest
</x-app-layout>