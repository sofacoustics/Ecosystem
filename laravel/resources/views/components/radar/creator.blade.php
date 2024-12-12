<!--
    See Views/Components/Radar/Creator.php class
-->
@props([
    'active' => 'bg-pink-600',
])
{{-- This ensures that Livewire can keep track of different elements in the loop when the loop changes. --}}
<div wire:key="creator.{{ $index }}">
    {{-- <legend>Creator</legend> --}}
    <div class="flex">
        <button type="button"
            @if ($creator->type() != 'person') title="Change to 'Person'"
        wire:click="setCreatorType({{ $index }}, 'person')"
        @else disabled @endif
            class="{{ $getButtonClass('person') }}">
            Person
        </button>
        <button type="button"
            @if ($creator->type() != 'institution') title="Change to 'Institute'"
        wire:click="setCreatorType({{ $index }}, 'institution')"
        @else disabled @endif
            class="{{ $getButtonClass('institution') }}">
            Institution
        </button>
    </div>
    <div class="relative flex flex-row">
        @if ($creator->type() === 'person')
            <x-wire-input label="givenName" attribute="{{ $model }}.givenName" class="{{ $class }}" />
            <x-wire-input label="familyName" attribute="{{ $model }}.familyName" class="{{ $class }}" />
            <x-wire-input label="ORCID ID" attribute="{{ $model }}.nameIdentifier.0.value"
                class="{{ $class }}" />
            @if ($creator->hasAffiliation())
                <x-wire-input label="creatorAffiliation" attribute="{{ $model }}.creatorAffiliation.value"
                    class="{{ $class }}" />
            @else
                <x-button-without-form wire:click="addCreatorAffiliation({{ $index }})" type="button"
                    title="Add Affiliation">
                    Add Affiliation
                </x-button-without-form>
            @endif
        @else
            <x-wire-input label="creatorName" attribute="{{ $model }}.creatorName"
                class="{{ $class }}" />
            {{-- <x-wire-input label="ORCID ID"
                attribute="{{ $model }}.nameIdentifier.0.value" />
                --}}
        @endif

        <x-button-without-form wire:click="removeCreator({{ $index }})" type="button"
            title="Remove the creator">Delete {{ $creator->type() }}</x-button-without-form>
    </div>

</div>
