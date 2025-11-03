<b>Size</b>: 
	@if($fileSizeInBytes>0)
		{{ $fileSizeInBytes }} bytes 
		@if (round($fileSizeInBytes / (1024*1024*1024), 2) >= 1)
			= {{ round($fileSizeInBytes / (1024*1024*1024), 2) }} GB.
		@elseif (round($fileSizeInBytes / (1024*1024), 2)>= 1)
			= {{ round($fileSizeInBytes / (1024*1024), 2) }} MB.
		@elseif (round($fileSizeInBytes / (1024), 2) >= 1)
				= {{ round($fileSizeInBytes / (1024), 2) }} kbytes.
		@else 
			.
		@endif
	@else
		File not in disk.
	@endif
<b>Date created</b>: 
	@if($createdAt)
		{{ $createdAt }}.
	@else	
		Date not available.
	@endif
<b>Date updated</b>: 
	@if($updatedAt)
		{{ $updatedAt }}.
	@else	
		Date not available.
	@endif