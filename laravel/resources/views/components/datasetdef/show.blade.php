{{--
    display a datasetdef

    Parameters:

        $datasetdef
--}}
    <b>Datafile Name:</b> {{ $datasetdef->name }}, <b>Datafile Type:</b> {{ $datasetdef->datafiletype->name }}
    @if($datasetdef->widget), <b>Linked Widget:</b> {{ $datasetdef->widget->name }} @endif
    @auth
        @if( Auth::user()->id  == $datasetdef->database->user_id)
            @if(count($datasetdef->database->datasets) == 0)
                <form class="bg-green-100 inline" action="{{ route('datasetdefs.edit', [$datasetdef]) }}">
                        <button type="submit" class="btn btn-danger btn-sm">Edit</button>
                </form>
                <form class="bg-red-100 inline" method="POST" action="{{ route('datasetdefs.destroy', [$datasetdef]) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            @endif
        @endif
    @endauth
