<x-app-layout>
    <x-slot name="header">
        <x-slot name="header">
			<x-database.header :datafile="$datafile" tabTitle="Datafile | {{ $datafile->name }}"/>
        </x-slot>
        <x-property name="Dataset">
            <a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a><br>
        </x-property>
        <x-property name="Datafile name">
            {{ $datafile->name }}
        </x-property>
        <x-property name="Datafiletype">
            {{ $datafile->datasetdef->datafiletype->name }}<br>
        </x-property>
		@hasrole('admin')
        <x-property name="RADAR id">
			{{ $datafile->radar_id }}
        </x-property>
		@endhasrole

        {{-- call view based on datafiletype --}}
        @livewire(DatafileListener::class, ['datafile' => $datafile])

		<x-button action="{{  asset($datafile->url()) }}">Download</x-button>
		@can('update', $datafile)
			@if($datafile->dataset->database->radar_id != null && $datafile->radar_id == null)
				<x-button method="POST" action="{{ route('datafiles.uploadtoradar', $datafile) }}">Upload to RADAR</x-button>
			@endif
		@endcan
		@can('delete', $datafile)
			@if($datafile->radar_id)
				<x-button method="DELETE" action="{{ route('datafiles.deletefromradar', $datafile) }}">Delete from RADAR</x-button>
			@endif
		@endcan
</x-app-layout>
