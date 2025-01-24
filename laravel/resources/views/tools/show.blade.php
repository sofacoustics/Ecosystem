<x-app-layout>
<x-slot name="header">
        <x-tools.header :tool=$tool />
</x-slot>

    <p><b>Description:</b> {{ $tool->description }}</p>
		<p><b>RADAR ID:</b> {{ $tool->radar_id }}</p>		
		<p><b>File Name:</b> {{ $tool->filename }}</p>
		<p><b>Link to documentation:</b> {{ $tool->linkdoc }}</p>
		<p><b>Link to code repository:</b> {{ $tool->linkcode }}</p>

@env('local')
    <ul class="list-disc list-inside">
    </div>
@endenv

</x-app-layout>
