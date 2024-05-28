<div>
    {{-- <p>datafiletype: 3</p>
    <p>tool: 1</p> --}}
    <p>HRTF rendered with images created by CreateFigures</p>
    {{-- <p>name: {{ $datafile->name }}</p>
    <p>png1: {{$datafile->name.'_1.png'}}</p>
    <p>asset: {{$datafile->asset()}}</p> --}}
    <x-img :asset="$datafile->asset('_1.png')" />
    <x-img :asset="$datafile->asset('_2.png')" />
    <x-img :asset="$datafile->asset('_3.png')" />
</div>
