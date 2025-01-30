<div>
    @can('update', $dataset)
        <form wire:submit="save">
            <input type="file" wire:model="file">
            @error('file')
                <span class="error">{{ $message }}</span>
            @enderror
            {{--        <button type="submit">Save file</button> --}}
        </form>
    @endcan
</div>
