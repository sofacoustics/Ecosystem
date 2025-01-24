<x-app-layout>
<x-slot name="header">
        <x-widgets.header :widget=$widget />
</x-slot>
    <h3>{{ $widget->name }}</h3>

    <p><b>Description:</b> {{ $widget->description }}</p>
		<p><b>Script Name:</b> {{ $widget->scriptname }}</p>		
		<p><b>Script Path:</b> {{ $widget->scriptpath }}</p>
		<p><b>Script Parameters:</b> {{ $widget->scriptparameters }}</p>
		<p><b>Function Name:</b> {{ $widget->functionname }}</p>

@env('local')

    <ul class="list-disc list-inside">
    </div>
@endenv

</x-app-layout>
