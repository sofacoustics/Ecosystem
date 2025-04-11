<div>
    @can('update', $dataset->database)
        <input type="file" wire:model="files" multiple>
        <button wire:click="uploadFiles">Upload Files</button>
    @endcan
</div>
