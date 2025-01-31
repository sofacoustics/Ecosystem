<div>
    @can('update', $dataset)
        <form wire:submit="save">
            <input type="file" max-size="10" wire:model="file">
            <x-error attribute='file' />
            {{-- <button type="submit">Save file</button> --}}
        </form>
    @endcan
</div>
