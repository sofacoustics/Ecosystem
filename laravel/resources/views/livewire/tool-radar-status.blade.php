{{--
	Get and display RADAR information about this tool
--}}
<div>
	<h3>RADAR Status</h3>
	<p>ID: {{ $id }}</p>
	<p>State: {{ $state }}</p>
	<p>DOI: {{ $doi }}</p>
	<p>Size: {{ $size }}</p>
	<p>Dataset: {{ $radar_content }}</p>

	@if("$error" != '')
		<x-alert title='Error!'>{{ $error }}</x-alert>
	@endif
</div>
