<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <p>This is the resourcetype livewire component view</p>
    <select wire:model.live="resourcetype">
        <option disabled value="">Select a resource type...</option>
        @foreach($resourcetypes as $r)
            <option value="{{ $r->value }}">{{ $r->value }}</option>
        @endforeach
    </select>
    Resource Type: {{ $resourcetype }}
</div>
