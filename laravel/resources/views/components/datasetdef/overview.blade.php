{{--
    An overview component for a datasetdef

    Parameters:

        datasetdef

--}}

Name: {{ $datasetdef->name }}, Database: {{ $datasetdef->database->title }}, Type: {{ $datasetdef->datafiletype->name }},
@if($datasetdef->tool)
    Tool: {{ $datasetdef->tool->name }}
@else
    Tool: none specified
@endif
