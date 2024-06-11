<div>
    @foreach ($dataset->datafiles as $datafile)
        @if (!$datafile->isImage())
            {{-- @livewire(DatafileListener::class, ['datafile' => $datafile]) --}}
            File: {{ $datafile->name }}
            <livewire:DatafileListener :datafile="$datafile" />
        @endif
    @endforeach

    <div class="flex flex-row">
    @foreach ($dataset->datafiles as $datafile)
        @if ($datafile->isImage())
            <x-img caption="{{ $datafile->name }}" class="p-2" asset="{{ $datafile->asset() }}" />
        @endif
    @endforeach
    </div>
</div>
