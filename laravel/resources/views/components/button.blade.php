<!-- A general purpose button
<form $attributes method="$method" action="$action"> -->
<form {{ $attributes }}>
        <input class="rounded-full border bg-amber-200 p-2 m-2" type="submit" value="{{ $slot }}">
</form>

