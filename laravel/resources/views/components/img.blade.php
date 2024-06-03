{{-- Set the defaults, since I can't seem to set them in the component class --}}
{{-- @props([
    'caption' => '',
    'asset' => '',
    'class' => ''
])
--}}
<div class="p-5">
    {{-- <p>img.blade.php</p> --}}
    @if ($asset == "")
        <object data="/images/the-image-is-being-generated.svg" type="image/svg+xml" width="200" height="200">
            <!-- Für Browser ohne SVG-Unterstützung -->
            </object>
    @else
        {{-- <p>asset: {{ $asset }}</p> --}}
        {{-- <img class="{{ $class }}" src="{{ $asset }}" width="400px"/> --}}

        <figure>
            <img src="{{ $asset }}">
            @if (isset($caption))
                <figcaption>File: {{ $caption }}</figcaption>
            @endif
        </figure>
    @endif
</div>
