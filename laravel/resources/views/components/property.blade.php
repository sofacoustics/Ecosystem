{{--
i   Component to output a property in the format

        name: description

    Parameters:

        name	The title to appear before the colon
        slot    The description to appear after the colon
--}}
<div>
    <b>{{ $name }}:</b> {{ $slot }}
</div>
