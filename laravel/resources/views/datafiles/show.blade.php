<x-app-layout>
	<x-slot name="header">
		<x-database.header :datafile="$datafile" tabTitle="Datafile | {{ $datafile->name }}"/>
	</x-slot>
		
	<x-property name="Dataset Name">
		<a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a><br>
	</x-property>
	<x-property name="Datafile Type">{{ $datafile->datasetdef->datafiletype->name }}<br></x-property>
	<x-property name="Datafile Name">
		<a href="{{  asset($datafile->url()) }}">{{ $datafile->name }}</a>
	</x-property>

	@if($datafile->radar_id)
		@hasrole('admin')
			<x-property name="RADAR id">{{ $datafile->radar_id }}</x-property>
		@endhasrole
	@endif

	@livewire(DatafileListener::class, ['datafile' => $datafile])

	<x-button action="{{  asset($datafile->url()) }}">Download</x-button>
	
</x-app-layout>
