{{--
    An list component for a datasetdef

    Parameters:

        datasetdef

--}}

<b>Name:</b> {{ $datasetdef->name }}, <b>Type:</b> {{ $datasetdef->datafiletype->name }}
@if($datasetdef->widget), <b>Linked Widget:</b> {{ $datasetdef->widget->name }} @endif
@role('admin') (ID: {{ $datasetdef->datafiletype->id }}), @endrole
