{{--
    A general purpose button

    Parameters:

        method     The form method.

    Example:

            <x-button 
                method="DELETE"
                action="{{ route('datasets.destroy', [$dataset]) }}"
                class="inline">
                DELETE
            </x-button>

            <x-button>
                Create
            </x-button>

--}}
@props([
    'method' => 'POST',
])
@php
    if($method=="GET")
        $formMethod="GET";
    else
        $formMethod="POST";
@endphp
@if($attributes->has('action'))
    <form {{ $attributes }} method="{{ $formMethod }}">
    @if(in_array("$method", ['DELETE','PATCH','POST','PUT']))
        @csrf
        @if(in_array("$method", ['DELETE','PATCH','PUT']))
            @method($method)
        @endif
    @endif
@endif
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold my-1 py-1 px-2 rounded">{{ $slot }}</button>
@if($attributes->has('action'))
    </form>
@endif
