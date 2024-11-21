<div>
    {{-- This is called by the Livewire component Radar/Dataset.php --}}

    <p>jw:tmp resource/views/livewire/radar/dataset.blade.php</p>

    <p>id: {{ $radardataset->id }}</p>
    <p>title: {{ $radardataset->descriptiveMetadata->title }}</p>
    <p>productionYear: {{ $radardataset->descriptiveMetadata->productionYear }}</p>

    <form wire:submit="save">
        <p>Title</p>
        <input type="text" wire:model="radardataset.descriptiveMetadata.title">
        <div>
            @error('radardataset.descriptiveMetadata.title') <span class="error">{{ $message }}</span> @enderror
        </div>
        <p>Production Year</p>
        <input type="text" wire:model="radardataset.descriptiveMetadata.productionYear">
        <div>
            @error('radardataset.descriptiveMetadata.productionYear') <span class="error">{{ $message }}</span> @enderror
        </div>
        <fieldset class="bg-green-100">
            <legend>Publishers</legend>
            {{-- https://forum.laravel-livewire.com/t/set-array-of-values-to-a-component-from-array-of-inputs-usingsame-wire-model/421 --}}
            @foreach ($radardataset->descriptiveMetadata->publishers->publisher as $key => $item)
                <fieldset class="bg-blue-100">
                    <legend>Publisher</legend>
                    <input wire:model='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.value' type="text" />
                    <input wire:model='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.schemeURI' type="text" />
                    <input wire:model='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.nameIdentifier' type="text" />
                    <input wire:model='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.nameIdentifierScheme' type="text" />
                </fieldset>
            @endforeach

            <div>
                @error('radardataset.descriptiveMetadata.productionYear') <span class="error">{{ $message }}</span> @enderror
            </div>


        </fieldset>
        <fieldset class="bg-green-100">
            <legend>Creators</legend>
            {{-- https://forum.laravel-livewire.com/t/set-array-of-values-to-a-component-from-array-of-inputs-usingsame-wire-model/421 --}}
            @foreach ($radardataset->descriptiveMetadata->creators->creator as $key => $item)
                <fieldset class="bg-blue-100 ml-2 mb-2">
                    <legend>Creator</legend>
                    <label for="givenName">
                        Given Name: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.givenName' type="text" />
                    </label>
                    <div>
                        @error("radardataset.descriptiveMetadata.creators.creator.$key.givenName") <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <label for="familyName">
                        Family Name: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.familyName' type="text" />
                    </label>
                    <div>
                        @error("radardataset.descriptiveMetadata.creators.creator.$key.familyName") <span class="error">{{ $message }}</span> @enderror
                    </div>
                    <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.creatorName' type="text" />
                    <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.nameIdentifier' type="text" />
                </fieldset>
            @endforeach

        </fieldset>
{{--        <input type="text" wire:model="form.title">
        <div>
            @error('form.title') <span class="error">{{ $message }}</span> @enderror
        </div>

        <input type="text" wire:model="form.content">
        <div>
            @error('form.content') <span class="error">{{ $message }}</span> @enderror
        </div> --}}
        @if (session('error'))
            <p>RADAR exception</p>
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <button type="submit">Save</button>
    </form>
</div>
