{{--
    display a datasetdef

    Parameters:        $datasetdef
		
		Unused (most probably), because this is now integrated in resources\views\databases\datasetdefs\index.blade.php		
		
--}}
@can('delete', $datasetdef)
    <x-button method="DELETE" class="inline" action="{{ route('datasetdefs.destroy', [$datasetdef]) }}">Delete</x-button>
@endcan
@auth
	@can('update', $datasetdef)
		<x-button method="GET" class="inline" action="{{ route('datasetdefs.edit', [$datasetdef]) }}">Edit</x-button>
	@endcan
@endauth
<x-datasetdef.list :datasetdef=$datasetdef />
		
{{-- <p>resources\views\components\datasetdef\show.blade.php</p> --}}
