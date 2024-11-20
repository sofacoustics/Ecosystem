<div>
    {{-- This is called by the Livewire component Radar/Dataset.php --}}

    <p>jw:tmp resource/views/livewire/radar/dataset.blade.php</p>

    <p>id: {{ $radardataset->id }}</p>
    <p>title: {{ $radardataset->descriptiveMetadata->title }}</p>
    <p>productionYear: {{ $radardataset->descriptiveMetadata->productionYear }}</p>

    <form wire:submit="save">
        <input type="text" wire:model="radardataset.descriptiveMetadata.title">
        <div>
            @error('radardataset.descriptiveMetadata.title') <span class="error">{{ $message }}</span> @enderror
        </div>
        <input type="text" wire:model="radardataset.descriptiveMetadata.productionYear">
        <div>
            @error('radardataset.descriptiveMetadata.productionYear') <span class="error">{{ $message }}</span> @enderror
        </div>
{{--        <input type="text" wire:model="form.title">
        <div>
            @error('form.title') <span class="error">{{ $message }}</span> @enderror
        </div>

        <input type="text" wire:model="form.content">
        <div>
            @error('form.content') <span class="error">{{ $message }}</span> @enderror
        </div> --}}

        <button type="submit">Save</button>
    </form>
</div>
