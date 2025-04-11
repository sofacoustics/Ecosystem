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
    <ul class="list-disc list-inside">
    @foreach ($allDatafiles as $datafile)
        <li><x-datafile.list :datafile=$datafile /></li>
    @endforeach
    </ul>
</x-app-layout>
