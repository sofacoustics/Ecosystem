<x-app-layout>
    <x-slot name="header">
        {{ $service->name }}<
    </x-slot>
    <p>{{ $service->description }}</p>
    <p>Exe: {{ $service->exe }}</p>
    <p>Parameters: {{ $service->parameters }}</p>
</x-app-layout>
