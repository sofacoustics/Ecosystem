<div>
    {{-- This is called by the Livewire component Radar/Dataset.php --}}

    <p>jw:tmp resource/views/livewire/radar/dataset.blade.php</p>

    <p>id: {{ $radardataset->id }}</p>
    <p>title: {{ $radardataset->descriptiveMetadata->title }}</p>
    <p>productionYear: {{ $radardataset->descriptiveMetadata->productionYear }}</p>

    <!-- https://v1.tailwindcss.com/components/forms -->
    <form wire:submit="save" class="w-full max-w-fit">
        <label>
            Title: <input type="text" wire:model="radardataset.descriptiveMetadata.title">
            <div>
                @error('radardataset.descriptiveMetadata.title')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </label>
        <p>Production Year</p>
        <input type="text" wire:model="radardataset.descriptiveMetadata.productionYear">
        <x-fielderror attribute='radardataset.descriptiveMetadata.productionYear' />
        <fieldset class="bg-green-100">
            <legend>Publishers</legend>
            {{-- https://forum.laravel-livewire.com/t/set-array-of-values-to-a-component-from-array-of-inputs-usingsame-wire-model/421 --}}
            @foreach ($radardataset->descriptiveMetadata->publishers->publisher as $key => $item)
                <fieldset class="bg-blue-100 ml-2 mb-2">
                    <legend>Publisher</legend>
                    <div class="relative flex flex-row">
                        <x-wire-input label="value"
                            attribute="radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.value"
                            class="md:w-1/4" />
                        <x-wire-select label="nameIdentifierScheme"
                            attribute='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.nameIdentifierScheme'
                            model="\App\Models\Radar\Metadataschema" field="name" searchterm="nameIdentifierScheme"
                            class="md:w-1/4" />
                        <x-wire-input label='schemeURI' readonly
                            attribute='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.schemeURI'
                            class="md:w-1/4" />
                        <x-wire-input label='nameIdentifier' readonly
                            attribute='radardataset.descriptiveMetadata.publishers.publisher.{{ $key }}.nameIdentifier'
                            class="md:w-1/4" />
                    </div>
                </fieldset>
            @endforeach

            <div>
                @error('radardataset.descriptiveMetadata.productionYear')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
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
                    @foreach (\App\Models\Radar\Metadataschema::where('name', 'resourceType')->get() as $r)
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
                        <select
                            wire:model.live='radardataset.descriptiveMetadata.subjectAreas.subjectArea.{{ $key }}.controlledSubjectAreaName'>
                            @foreach (\App\Models\Radar\Metadataschema::where('name', 'subjectArea')->get() as $r)
                                <option value="{{ $r->value }}">{{ $r->display }}</option>
                            @endforeach
                        </select>
                    </label>
                    @if ($subjectArea->controlledSubjectAreaName === 'OTHER')
                        <label for="additionalSubjectAreaName">
                            Additional Subject Area Name: <input
                                wire:model='radardataset.descriptiveMetadata.subjectAreas.subjectArea.{{ $key }}.additionalSubjectAreaName'
                                type="text" placeholder="Free text description of the subject area" />
                            @error("radardataset.descriptiveMetadata.subjectAreas.subjectArea.$key.additionalSubjectAreaName")
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </label>
                    @endif
                    <x-button-without-form wire:click="removeSubjectArea({{ $key }})"
                        title="Remove this subject area">Delete</x-button-without-form>
                </fieldset>
            @endforeach
            <x-button-without-form wire:click="addSubjectArea" title="Add new subject area">Add</x-button-without-form>

            {{-- <pre>{{ $radardataset->descriptiveMetadata->subjectAreas->toJson(JSON_PRETTY_PRINT) }}</pre> --}}


        </fieldset>
        <fieldset class="bg-red-300">
            <legend>Rights</legend>
            <label>
                Controlled Rights:
                <select wire:model.live='radardataset.descriptiveMetadata.rights.controlledRights'>
                    @foreach (\App\Models\Radar\Metadataschema::where('name', 'rights')->get() as $r)
                        <option value="{{ $r->value }}">{{ $r->display }}</option>
                    @endforeach
                </select>
                @if ($radardataset->descriptiveMetadata->rights->controlledRights === 'OTHER')
                    <label for="additionalRights">
                        Additional Rights: <input wire:model='radardataset.descriptiveMetadata.rights.additionalRights'
                            type="text" />
                        @error('radardataset.descriptiveMetadata.rights.additionalRights')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </label>
                @endif
        </fieldset>

        <fieldset class="bg-green-100">
            <legend>Rights Holders</legend>
            {{-- https://forum.laravel-livewire.com/t/set-array-of-values-to-a-component-from-array-of-inputs-usingsame-wire-model/421 --}}
            @foreach ($radardataset->descriptiveMetadata->rightsHolders->rightsHolder as $rightsHolderKey => $rightsHolder)
                <fieldset class="bg-blue-100 ml-2 mb-2">
                    <legend>Rights Holder</legend>
                    <div class="relative flex flex-wrap -mx-3 mb-6">
                        <!-- another possibility would be https://codebyrj.com/laravel-livewire/creating-a-searchable-select-box-component-with-livewire-3-alpine-js-and-tailwindcss -->
                        <input type="text" class="form-input" placeholder="Search ROR for institution..."
                            wire:model.live="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.value"
                            wire:keydown.escape="rorReset" wire:keydown.tab="rorReset"
                            wire:keydown.arrow-up="decrementHighlight" wire:keydown.arrow-down="incrementHighlight"
                            wire:keydown.enter="rorSelectEntry" x-on:blur="$wire.rorReset()" />
                        <div id="rorResults" class="absolute top-full z-40 bg-white">
                            @if (!empty($rorResults) && $currentRightsHolder == $rightsHolderKey)
                                <!-- followed these instructions: https://laracasts.com/discuss/channels/livewire/autocomplete-using-livewire -->
                                <ul id="rorResults">
                                    @foreach ($rorResults->items as $key => $item)
                                        @foreach ($item->names as $name)
                                            @if ($name->lang === 'en')
                                                <li class="hover:bg-violet-100"><a href='#'
                                                        wire:click.prevent='rorSet("{{ $rightsHolderKey }}", "{{ $item->id }}", "{{ $name->value }}")'>{{ $name->value }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <x-wire-select label="nameIdentifierScheme"
                            attribute='radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.nameIdentifierScheme'
                            model="\App\Models\Radar\Metadataschema" field="name" searchterm="nameIdentifierScheme"
                            class="md:w-1/4" />
                        <x-wire-input label="schemeURI" readonly
                            attribute="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.schemeURI"
                            class="md:w-1/4" />
                        <x-wire-input label="nameIdentifier" readonly
                            attribute="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.nameIdentifier"
                            class="md:w-1/4" />
                    </div>

                </fieldset>
            @endforeach
        </fieldset>
        <fieldset class="bg-green-200">
            <legend>Creators</legend>
            {{-- https://forum.laravel-livewire.com/t/set-array-of-values-to-a-component-from-array-of-inputs-usingsame-wire-model/421 --}}
            @foreach ($radardataset->descriptiveMetadata->creators->creator as $key => $creator)
                <x-radar.creator index="{{ $key }}" :data=$creator
                    model="radardataset.descriptiveMetadata.creators.creator.{{ $key }}" />
            @endforeach
            <x-button-without-form wire:click="addCreator" title="Add new creator">Add</x-button-without-form>
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

        {{-- <button type="submit">Save</button> --}}
        <x-button-without-form type="submit">Save to RADAR</x-button-without-form>
        <div wire:loading>Saving...</div>
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

        @if (session('status'))
            {{-- https://laracasts.com/discuss/channels/javascript/hiding-element-after-x-seconds-with-alpine?page=1&replyId=643299 --}}
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                {{ session('status') }}
            </div>
        @endif
    </form>
</div>
