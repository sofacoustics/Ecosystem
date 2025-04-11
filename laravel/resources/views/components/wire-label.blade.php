@props([
    'for' => ''
])
<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
    <label
        for="$for"
    >{{ $slot }}</label>
</div>
