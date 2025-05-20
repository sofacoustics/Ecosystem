<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database />
	</x-slot>

<p>{{ $body }}</p>
@if($database->radar_id != null)
<p>RADAR Dataset ID: {{ $database->radar_id }}</p>
@endif
</x-app-layout>
