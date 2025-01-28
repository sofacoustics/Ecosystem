{{--
    A dataset component to display a dataset as an entry in a list

    Parameters:

        $dataset    The dataset we want to display
        $link       'true' if url link should be used, 'false' otherwise
--}}
@props([
    'link' => 'true',
    'dataset' => null,
])

@if($link=='true')
    <a href="{{ route('datasets.show', $dataset->id) }}">
@endif
Name: {{ $dataset->name }}
@role('admin') (ID: {{ $dataset->id }}) @endrole
@if($link)
    </a>
@endif
