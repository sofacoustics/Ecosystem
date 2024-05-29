<div>
    <input wire:model='message' type="text">
    <button wire:click='submitMessage'>Submit</button>
    @foreach ($conversation as $message)
       <p>{{ $message }}</p>
    @endforeach
</div>
