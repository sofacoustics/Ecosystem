<div>
    <p>datafile-listener.blade.php</p>
    {{-- print_r($datafile) --}}
    <p>id: {{ $id }}</p>
    <p>datafiletype = {{ $datafile->datasetdef->datafiletype }} </p>
    <p>datafiletype id = {{ $datafile->datasetdef->datafiletype->id }} </p>
    <p>tool = {{ $datafile->datasetdef->tool }} </p>
    <img width="400" src="{{ $datafile->asset() }}"/>
    <x-img :asset={{ $datafile->asset() }} />
</div>
