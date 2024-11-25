<div>
    {{-- This is called by the Livewire component Radar/Dataset.php --}}

    <p>jw:tmp resource/views/livewire/radar/dataset.blade.php</p>

    <p>id: {{ $radardataset->id }}</p>
    <p>title: {{ $radardataset->descriptiveMetadata->title }}</p>
    <p>productionYear: {{ $radardataset->descriptiveMetadata->productionYear }}</p>

    <form wire:submit="save">
        <label>
            Title: <input type="text" wire:model="radardataset.descriptiveMetadata.title">
            <div>
                @error('radardataset.descriptiveMetadata.title') <span class="error">{{ $message }}</span> @enderror
            </div>
        </label>
        <p>Production Year</p>
        <input type="text" wire:model="radardataset.descriptiveMetadata.productionYear">
        <div>
            @error('radardataset.descriptiveMetadata.productionYear') <span class="error">{{ $message }}</span> @enderror
        </div>
        <fieldset class="bg-green-100">
            <legend>Publishers</legend>
            {{-- https://forum.laravel-livewire.com/t/set-array-of-values-to-a-component-from-array-of-inputs-usingsame-wire-model/421 --}}
            @foreach ($radardataset->descriptiveMetadata->publishers->publisher as $key => $item)
                <fieldset class="bg-blue-100 ml-2 mb-2">
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
            @foreach ($radardataset->descriptiveMetadata->creators->creator as $key => $creator)
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
                        <div>
                            @error("radardataset.descriptiveMetadata.creators.creator.$key.familyName") <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </label>
                    <label for="creatorName">
                        Creator Name: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.creatorName' type="text" />
                        <div>
                            @error("radardataset.descriptiveMetadata.creators.creator.$key.creatorName") <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </label>
                    @foreach($creator as $creatorfieldkey => $creatorfieldvalue)
                        @if($creatorfieldkey == "nameIdentifier")
                            <fieldset class="bg-pink-100 ml-2 mb-2">
                                <legend>Name Identifier</legend>
                                <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.nameIdentifier.0.value' type="text" />
                                <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.nameIdentifier.0.schemeURI' type="text" />
                                <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.nameIdentifier.0.nameIdentifierScheme' type="text" />
                            </fieldset>
                        @endif
                        @if($creatorfieldkey == "creatorAffiliation")
                            @if(is_array($creatorfieldvalue))
                                <fieldset class="bg-yellow-100 ml-2 mb-2">
                                    <legend>Creator Affiliation</legend>
                                    <label>
                                        Value: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.creatorAffiliation.value' type="text" />
                                    </label>
                                    <label>
                                        schemeURI: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.creatorAffiliation.schemeURI' type="text" />
                                    </label>
                                    <label>
                                        affiliationIdentifier: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.creatorAffiliation.affiliationIdentifier' type="text" />
                                    </label>
                                    <label>
                                        affiliationIdentifierScheme: <input wire:model='radardataset.descriptiveMetadata.creators.creator.{{ $key }}.creatorAffiliation.affiliationIdentifierScheme' type="text" />
                                    </label>
                                </fieldset>
                            @endif
                        @endif
                    @endforeach
                </fieldset>
            @endforeach
        </fieldset>
        <fieldset class="bg-purple-100">
            <legend>Resource</legend>
            <label>
                Value: <input wire:model='radardataset.descriptiveMetadata.resource.value' type="text" />
            </label>
            <label>
                {{--
                resourceType: <input wire:model='radardataset.descriptiveMetadata.resource.resourceType' type="text" />
                <select name="resourceType">
                    <option value="none" selected disabled hidden>Select a value</option>
                    <option value="Audiovisual">Audiovisual</option>
                    <option value="Collection">Collection</option>
                </select> --}}
                {{-- https://livewire.laravel.com/docs/wire-model#select-dropdowns --}}
                <select wire:model='radardataset.descriptiveMetadata.resource.resourceType'>
                    @foreach(\App\Models\Radar\Metadataschema::where('name','resourceType')->get() as $r)
                        <option value="{{ $r->value }}">{{ $r->display }}</option>
                    @endforeach
                </select>

                {{-- nested items are a little bit tricky, I believe
                <livewire:radar.schema.resourcetype /> --}}
            </label>
        </fieldset>
        <fieldset class="bg-red-100">
            <legend>Subject Areas</legend>
            @foreach ($radardataset->descriptiveMetadata->subjectAreas->subjectArea as $key => $subjectArea)
                <fieldset class="bg-blue-100 ml-2 mb-2">
                    <legend>SubjectArea</legend>
                    <label for="controlledSubjectArea">
                        <select wire:model='radardataset.descriptiveMetadata.subjectAreas.subjectArea.{{ $key }}.controlledSubjectAreaName'>
                            @foreach(\App\Models\Radar\Metadataschema::where('name','subjectArea')->get() as $r)
                                <option value="{{ $r->value }}">{{ $r->display }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label for="additionalSubjectAreaName">
                        Additional Subject Area Name: <input wire:model='radardataset.descriptiveMetadata.subjectAreas.subjectArea.{{ $key }}.additionalSubjectAreaName' type="text" />
                        <div>
                            @error("radardataset.descriptiveMetadata.subjectAreas.subjectArea.$key.additionalSubjectAreaName") <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </label>
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
        @if (session('status'))
            {{-- https://laracasts.com/discuss/channels/javascript/hiding-element-after-x-seconds-with-alpine?page=1&replyId=643299 --}}
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                {{ session('status') }}
            </div>
        @endif
    </form>
</div>
