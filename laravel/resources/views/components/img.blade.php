<div>
    <p>img.blade.php</p>
    @if($asset == "")
        <object data="/images/the-image-is-being-generated.svg" type="image/svg+xml" width="200" height="200">
            <!-- Für Browser ohne SVG-Unterstützung -->
            </object>
                
    @else
    <p>asset: {{ $asset }}</p>
    <img src="{{ $asset }}" width="400px">
        
    @endif
</div>