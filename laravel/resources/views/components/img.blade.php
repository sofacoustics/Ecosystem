{{-- Set the defaults, since I can't seem to set them in the component class --}}
@props([
    'caption' => '',
    'asset' => '',
    'class' => ''
])
<div>
	@if ($asset == "")
		<figure class="flex justify-center items-center h-full">
			<object data="/images/no-data-uploaded-yet.svg" type="image/svg+xml" width="200" height="200">
				<!-- Für Browser ohne SVG-Unterstützung -->
			</object>
		</figure>
	@else
		<figure class="flex justify-center items-center h-full">
			<a href="{{ $asset }}" target="_blank">
				<img class="{{ $class }}" src="{{ $asset }}"  width="400px"/>
			</a>
			@if (isset($caption))
				<figcaption>{{ $caption }}</figcaption>
			@endif
		</figure>
	@endif
</div>
