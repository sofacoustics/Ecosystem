@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = "text-gray-700 mb-2 block font-bold";
    $selectClass = "form-control text-gray-700 rounded-lg mb-2 block font-bold";
    $inputClass = "text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none";
    $buttonClass = "bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white";
@endphp
<div>
    @auth
        @if (Auth::user()->id == $database->user_id)
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label for="creatorName" class="{{ $labelClass }}">Name: (*)</label>
                    <input wire:model="creatorName" type="text" id="creatorName"
                        class="{{ $inputClass }}" required />
                    @error('creatorName')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="givenName" class="{{ $labelClass }}">Given Name:</label>
                    <input wire:model="givenName" type="text" id="givenName"
                        class="{{ $inputClass }}" />
                    @error('givenName')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="familyName" class="{{ $labelClass }}">Family Name:</label>
                    <input wire:model="familyName" type="text" id="familyName"
                        class="{{ $inputClass }}" />
                    @error('familyName')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="block">
                    <label class="{{ $labelClass }}" for="nameIdentifierSchemeIndex">Name Identifier Scheme:</label>
                    <select class="{{ $selectClass }}" id="nameIdentifierSchemeIndex" wire:model="nameIdentifierSchemeIndex">
                        <option value="">Select an identifier scheme</option>
												<option value="0">Other</option>
												<option value="1">ORCID</option>                        
												<option value="2">ROR</option>
                    </select>
                    @error('nameIdentifierSchemeIndex')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
								
                <div class="mb-4">
                    <label for="nameIdentifier" class="{{ $labelClass }}">Name Identifier:</label>
                    <input wire:model="nameIdentifier" type="text" id="nameIdentifier"
                        class="{{ $inputClass }}" />
                    @error('nameIdentifier')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="creatorAffiliation" class="{{ $labelClass }}">Creator Affiliation:</label>
                    <input wire:model="creatorAffiliation" type="text" id="creatorAffiliation"
                        class="{{ $inputClass }}" />
                    @error('creatorAffiliation')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="{{ $buttonClass }}">
                        {{ $creator ? 'Update Creator' : 'Create Creator' }}
                    </button>
                </div>

            </form>
        @endif
    @endauth
</div>
