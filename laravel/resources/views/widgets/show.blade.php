<x-app-layout>
    <x-slot name="header">
        <x-widgets.header :widget=$widget />
    </x-slot>
    <p>{{ $widget->description }}</p>
    <p><b>View:</b> {{ $widget->view }}</p>
@if(!is_null($widget->service))
    <p><b>Service:</b> {{ $widget->service->name }}</p>
@endif
</x-app-layout>
