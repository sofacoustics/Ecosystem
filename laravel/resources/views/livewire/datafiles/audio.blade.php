<div>
	<p><b>Size</b>: {{ $fileSizeInBytes }} bytes 
		@if($fileSizeInKilobytes > 0)
		= {{ $fileSizeInKilobytes }} kbytes 
			@if($fileSizeInMegabytes > 0)
				= {{ $fileSizeInMegabytes }} MB 
				@if($fileSizeInGigabytes)
					= {{ $fileSizeInGigabytes }} GB
				@endif
			@endif
		@endif
		.
		<b>Date created</b>: {{ $created_at }}.
	  <b>Date updated</b>: {{ $updated_at }}.
	</p>

     <audio controls>
         <source src="{{ $datafile->asset() }}">
           Your browser does not support the audio element.
     </audio>
</div>

