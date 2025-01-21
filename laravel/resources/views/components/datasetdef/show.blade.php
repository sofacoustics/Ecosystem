{{--
    display a datasetdef

    Parameters:

        $datasetdef
--}}
<div class="ml-5">
    Name: {{ $datasetdef->name }}. Datafiletype: {{ $datasetdef->datafiletype->name }}
    @if($datasetdef->tool)Tool: {{ $datasetdef->tool->name }} @endif
    @auth
        @if( Auth::user()->id  == $datasetdef->database->user_id)
            @if(count($datasetdef->database->datasets) == 0)
                <form class="bg-red-100 inline" method="POST" action="{{ route('datasetdefs.destroy', [$datasetdef]) }}">
                        @csrf @method('DELETE')
                        (<button type="submit" class="btn btn-danger btn-sm">Delete</button>)
                </form>
                <form class="bg-red-100 inline" action="{{ route('datasetdefs.edit', [$datasetdef]) }}">
                        (<button type="submit" class="btn btn-danger btn-sm">Edit</button>)
                </form>
            @endif
        @endif
    @endauth
</div>
