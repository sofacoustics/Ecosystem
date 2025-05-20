<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Datasets
        </h2>
    </x-slot>
    <p>The following RADAR {{ count($datasets) }} datasets are available</p>
    <ul>
		@if (isset($datasets))
			@foreach ($datasets as $dataset)
				<li> {{ $dataset->descriptiveMetadata->title }} </li>
			@endforeach
			{{--
			@foreach ($datasets as $dataset)
			<li> {{ $dataset['descriptiveMetadata']['title'] }} </li>
			@endforeach
			--}}
		@else
			<p>There are not datasets in this workspace!</p>
		@endif
		@if("$error" != "")
			<p>ERROR: {{ $error }}</p>
		@endif
    </ul>
</x-app-layout>
