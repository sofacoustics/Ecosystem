<!-- output a field error -->
@props(['attribute'])

@error("$attribute")
    <div>
        <span class="bg-red-500 error">{{ $message }}</span>
    </div>
@enderror
