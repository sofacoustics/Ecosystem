<div>
@auth
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <form wire:submit="save">
        <input type="text" wire:model="name">
        <div>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>
        <input type="text" wire:model="description">
        <div>
            @error('description') <span class="error">{{ $message }}</span> @enderror
        </div>
         <button type="submit">Save</button>
    </form>
@endauth
</div>
