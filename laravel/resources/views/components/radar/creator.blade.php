<!--
    See Views/Components/Radar/Creator.php class
-->
@props([])
<div>
    <p>The Creator component</p>
    <legend>Creator</legend>
    <button type="button" wire:click="setCreatorType({{ $index }}, 'person')">
            Person
    </button>
    <button type="button" wire:click="setCreatorType({{ $index }}, 'institution')">
            Institution
    </button>
    @if ($creator->type() === 'person')
        <p>Person</p>
        <div class="relative flex flex-row">
            <x-wire-input label="givenName" attribute="{{ $model }}.givenName" class="{{ $class }}" />
            <x-wire-input label="givenName" attribute="{{ $model }}.familyName" class="{{ $class }}" />
            <x-wire-input label="ORCID ID" attribute="{{ $model }}.nameIdentifier.0.value"
                class="{{ $class }}" />
            <x-button-without-form wire:click="removeCreator({{ $index }})"
                title="Remove the creator">Delete</x-button-without-form>
        </div>
    @else
        <p>Institution</p>
        <div class="relative flex flex-row">
            <x-wire-input label="creatorName" attribute="{{ $model }}.creatorName" class="{{ $class }}" />
            {{-- <x-wire-input label="ORCID ID"
                attribute="{{ $model }}.nameIdentifier.0.value" />
                --}}
            <x-button-without-form wire:click="removeCreator({{ $index }})"
                title="Remove the creator">Delete</x-button-without-form>
        </div>
    @endif

</div>
