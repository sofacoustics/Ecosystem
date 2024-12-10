<!--
    See Views/Components/Radar/Creator.php class
-->
@props([
    'active' => 'bg-pink-600',
])
<div>
    <p>The Creator component</p>
    <legend>Creator</legend>
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
    @if ($creator->type() === 'person')
        <div class="flex flex-row">
            <x-wire-input label="givenName" attribute="{{ $model }}.givenName" class="{{ $class }}" />
            <x-wire-input label="familyName" attribute="{{ $model }}.familyName" class="{{ $class }}" />
            <x-wire-input label="ORCID ID" attribute="{{ $model }}.nameIdentifier.0.value"
                class="{{ $class }}" />
            <x-button-without-form wire:click="removeCreator({{ $index }})"
                title="Remove the creator">Delete</x-button-without-form>
        </div>
    @else
        <div class="relative flex flex-row">
            <x-wire-input label="creatorName" attribute="{{ $model }}.creatorName"
                class="{{ $class }}" />
            {{-- <x-wire-input label="ORCID ID"
                attribute="{{ $model }}.nameIdentifier.0.value" />
                --}}
            <x-button-without-form wire:click="removeCreator({{ $index }})"
                title="Remove the creator">Delete</x-button-without-form>
        </div>
    @endif

</div>
