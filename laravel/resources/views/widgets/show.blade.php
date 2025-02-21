<x-app-layout>
    <x-slot name="header">
        <x-widgets.header :widget=$widget />
    </x-slot>
    <p>{{ $widget->description }}</p>
    <p><b>View:</b> {{ $widget->view }}</p>
    <p><b>Service:</b> {{ $widget->service->name }}</p>
</x-app-layout>
