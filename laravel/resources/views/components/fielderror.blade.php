<!-- output a field error -->
@props(['attribute'])

@error("$attribute")
    <div>
        <span class="error">{{ $message }}</span>
    </div>
@enderror
