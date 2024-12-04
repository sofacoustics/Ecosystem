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
                @error('radardataset.descriptiveMetadata.title') <span class="error">{{ $message }}</span> @enderror
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
                        <select wire:model.live='radardataset.descriptiveMetadata.subjectAreas.subjectArea.{{ $key }}.controlledSubjectAreaName' >
                            @foreach(\App\Models\Radar\Metadataschema::where('name','subjectArea')->get() as $r)
                                <option value="{{ $r->value }}">{{ $r->display }}</option>
                            @endforeach
                        </select>
                    </label>
                    @if($subjectArea->controlledSubjectAreaName === 'OTHER')
                        <label for="additionalSubjectAreaName">
                            Additional Subject Area Name: <input wire:model='radardataset.descriptiveMetadata.subjectAreas.subjectArea.{{ $key }}.additionalSubjectAreaName' type="text" placeholder="Free text description of the subject area" />
                                @error("radardataset.descriptiveMetadata.subjectAreas.subjectArea.$key.additionalSubjectAreaName") <span class="error">{{ $message }}</span> @enderror
                        </label>
                    @endif
                    <x-button-without-form wire:click="removeSubjectArea({{ $key }})" title="Remove this subject area" >Delete</x-button-without-form>
                </fieldset>
            @endforeach
            <x-button-without-form wire:click="addSubjectArea" title="Add new subject area" >Add</x-button-without-form>

            {{--<pre>{{ $radardataset->descriptiveMetadata->subjectAreas->toJson(JSON_PRETTY_PRINT) }}</pre> --}}


        </fieldset>
        <fieldset class="bg-red-300">
            <legend>Rights</legend>
            <label>
                Controlled Rights:
                <select wire:model.live='radardataset.descriptiveMetadata.rights.controlledRights' >
                    @foreach(\App\Models\Radar\Metadataschema::where('name','rights')->get() as $r)
                        <option value="{{ $r->value }}">{{ $r->display }}</option>
                    @endforeach
                </select>
                @if($radardataset->descriptiveMetadata->rights->controlledRights === 'OTHER')
                    <label for="additionalRights">
                        Additional Rights: <input wire:model='radardataset.descriptiveMetadata.rights.additionalRights' type="text" />
                        @error("radardataset.descriptiveMetadata.rights.additionalRights") <span class="error">{{ $message }}</span> @enderror
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
                      <input type="text" class="form-input" placeholder="Search ROR for institution..." wire:model.live="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.value" wire:keydown.escape="rorReset" wire:keydown.tab="rorReset" wire:keydown.arrow-up="decrementHighlight" wire:keydown.arrow-down="incrementHighlight" wire:keydown.enter="rorSelectEntry" x-on:blur="$wire.rorReset()" />
					  <div id="rorResults" class="absolute top-full z-40 bg-white">
					    @if(!empty($rorResults) && $currentRightsHolder == $rightsHolderKey)
						  <!-- followed these instructions: https://laracasts.com/discuss/channels/livewire/autocomplete-using-livewire -->
						  <ul id="rorResults">
							@foreach($rorResults->items as $key => $item)
								@foreach($item->names as $name)
									@if($name->lang === "en")
										<li class="hover:bg-violet-100"><a href='#' wire:click.prevent='rorSet("{{ $rightsHolderKey }}", "{{ $item->id }}", "{{ $name->value }}")'>{{ $name->value }}</a></li>
									@endif
								@endforeach
							@endforeach
						  </ul>
  					@endif
				  </div>
					{{--	<x-wire-input label="value" attribute="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.value" class="w-full md:w-1/4 px-3 mb-6 md:mb-0" /> --}}
						{{-- <x-wire-input label="nameIdentifierScheme" attribute="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.nameIdentifierScheme" class="w-full md:w-1/4 px-3 mb-6 md:mb-0" />p --}}
						<div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
							<label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" >nameIdentifierScheme</label>
							<select wire:model.live='radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.nameIdentifierScheme' class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
								@foreach(\App\Models\Radar\Metadataschema::where('name','nameIdentifierScheme')->get() as $r)
									<option value="{{ $r->value }}">{{ $r->display }}</option>
								@endforeach
							</select>
						</div>
					  </div>
                      <div> schemeURI:  {{ $radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$rightsHolderKey]->schemeURI }} </div>
                      <div> nameIdentifier:  {{ $radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$rightsHolderKey]->nameIdentifier }} </div>
						{{-- <x-wire-input readonly label="schemeURI" attribute="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.schemeURI" class="w-full md:w-1/4 px-3 mb-6 md:mb-0" /> 
						<x-wire-input label="nameIdentifier" attribute="radardataset.descriptiveMetadata.rightsHolders.rightsHolder.{{ $rightsHolderKey }}.nameIdentifier" class="w-full md:w-1/4 px-3 mb-6 md:mb-0" /> --}}

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

        {{--<button type="submit">Save</button>--}}
        <x-button-without-form>Save to RADAR</x-button-without-form>
        @if (session('status'))
            {{-- https://laracasts.com/discuss/channels/javascript/hiding-element-after-x-seconds-with-alpine?page=1&replyId=643299 --}}
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                {{ session('status') }}
            </div>
        @endif
    </form>
</div>
