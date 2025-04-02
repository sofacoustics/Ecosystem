{{--
	An list component for a datasetdef

	Parameters:        datasetdef

		Unused (most probably), because this is now integrated in resources\views\databases\datasetdefs\index.blade.php		

--}}

<b>Name:</b> {{ $datasetdef->name }}, <b>Type:</b> {{ $datasetdef->datafiletype->name }}
@if($datasetdef->widget), <b>Linked Widget:</b> {{ $datasetdef->widget->name }} @endif
@role('admin') (ID: {{ $datasetdef->datafiletype->id }}), @endrole
