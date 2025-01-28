{{--
    display a datasetdef

    Parameters:

        $datasetdef
--}}
<x-datasetdef.list :datasetdef=$datasetdef />
{{--
<b>Datafile Name:</b> {{ $datasetdef->name }}, <b>Datafile Type:</b> {{ $datasetdef->datafiletype->name }}
@if($datasetdef->widget), <b>Linked Widget:</b> {{ $datasetdef->widget->name }} @endif
@role('admin') (ID: {{ $datasetdef->id }}) @endrole
--}}
@can('delete', $datasetdef)
    <x-button
        method="DELETE"
        action="{{ route('datasetdefs.destroy', [$datasetdef]) }}"
        class="inline">
        Delete
    </x-button>
@endcan
@can('update', $datasetdef)
    <x-button
        method="GET"
        action="{{ route('datasetdefs.edit', [$datasetdef]) }}"
        class="inline">
        Edit
    </x-button>
@endcan
<p>resources\views\components\datasetdef\show.blade.php</p>
