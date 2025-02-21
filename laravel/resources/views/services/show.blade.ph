<x-app-layout>
    <x-slot name="header">
        Services
    </x-slot>
    <p>A backend service can be specified in a widget and do background processing on datafiles. Currently the following services are available:</p>
    <table>
    <thead>

    </thead>
    @foreach($services as $service)

        {{ $service->name }}
        <p>{{ $service->description }}</p>
        <p>Exe: {{ $service->exe}}</p>
        <p>Parameters: {{ $service->parameters}}</p>
    @endforeach
    </table>
</x-app-layout>
