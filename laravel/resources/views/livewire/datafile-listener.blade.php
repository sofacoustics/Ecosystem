<div>
{{--
    <p>datafile-listener.blade.php</p>
    <p>id: {{ $id }}</p>
    <p>datafiletype = {{ $datafile->datasetdef->datafiletype }} </p>
    <p>datafiletype id = {{ $datafile->datasetdef->datafiletype->id }} </p>
    <p>widget = {{ $datafile->datasetdef->widget }} </p>
--}}
{{--    <img width="400" src="{{ $datafile->asset() }}"/> --}}
    <x-img asset="{{ $datafile->asset() }}" />
</div>
