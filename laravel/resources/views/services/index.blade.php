<x-app-layout>
    <x-slot name="header">
        Services
    </x-slot>
    <p>A backend service can be specified in a widget and do background processing on datafiles. Currently the following
        services are available:</p>
    <table class="text-left">
        <caption  class="text-left">
            Services
        </caption>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td><a href="{{ route('services.show', $service->id) }}">{{ $service->name }} </a></td>
                    <td> {{ $service->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-app-layout>
