<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database:  {{ $database->title }}
        </h2>
        {{ $database->description }}
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

    @can('update', $database)
			<livewire:database-form :database=$database />
    @else
        <p>BUG: You may not edit this database! You should not be able to access this page. Please report this to the webmaster.</p>
    @endcan
    @guest
        <p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
    @endguest


</x-app-layout>
