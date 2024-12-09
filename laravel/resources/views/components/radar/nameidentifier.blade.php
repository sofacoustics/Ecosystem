@props([
    'attribute' => ''
])

<div class="relative flex flex-row">
    <x-wire-select label="nameIdentifierScheme"
        attribute="{{ $attribute }}.nameIdentifierScheme"
        model="\App\Models\Radar\Metadataschema"
        field="name"
        searchterm="nameIdentifierScheme"
        class="md:w-1/3" />
    <x-wire-input label="schemeURI" readonly
        attribute="{{ $attribute }}.schemeURI"
        class="md:w-1/3" />
    <x-wire-input label="value" readonly
        attribute="{{ $attribute }}.value"
        class="md:w-1/3" />
</div>
