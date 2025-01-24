{{--
    An list component for a datasetdef

    Parameters:

        datasetdef

--}}

Name: {{ $datasetdef->name }}, Database: {{ $datasetdef->database->title }}, Type: {{ $datasetdef->datafiletype->name }},
@if($datasetdef->widget)
    Widget: {{ $datasetdef->widget->name }}
@else
    Widget: none specified
@endif

