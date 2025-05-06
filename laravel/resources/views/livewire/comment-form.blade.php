@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = 'text-gray-700 mb-2 block font-bold';
    $selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
    $inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
    @can('create', \App\Models\Database::class)
        @if ($comment)
            <h3>Edit a comment:</h3>
        @else
            <h3>Add a new comment:</h3>
        @endif
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label for="text" class="{{ $labelClass }}">Comment: (*)</label>
                <input wire:model="text" type="text" id="text" class="{{ $inputClass }}" required />
                @error('text')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="{{ $buttonClass }}">
                    {{ $comment ? 'Update comment' : 'Create comment' }}
                </button>
            </div>

        </form>
    @endcan
</div>
