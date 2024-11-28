<!-- A general purpose button -->
<input {{ $attributes->merge(['class' => 'rounded text-white bg-amber-700 p-1 m-1']) }} class="rounded border text-white bg-amber-700 p-1 m-1" type="submit" value="{{ $slot }}">
