<x-app-layout>
<x-slot name="header">
        <x-tools.header :tool=$tool />
</x-slot>
    <h3>{{ $tool->name }}</h3>

    <p><b>Description:</b> {{ $tool->description }}</p>
		<p><b>Script Name:</b> {{ $tool->scriptname }}</p>		
		<p><b>Script Path:</b> {{ $tool->scriptpath }}</p>
		<p><b>Script Parameters:</b> {{ $tool->scriptparameters }}</p>
		<p><b>Function Name:</b> {{ $tool->functionname }}</p>

@env('local')

    <ul class="list-disc list-inside">
    </div>
@endenv

</x-app-layout>
