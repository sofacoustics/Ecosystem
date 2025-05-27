{{--
	Get and display RADAR information about this database
--}}
<div>
	<h3>RADAR Status</h3>
	<p>ID: {{ $id }}</p>
	<p>State: {{ $state }}</p>
	<p>DOI: {{ $doi }}</p>
	<p>Size: {{ $size }}</p>

	@if("$error" != '')
		<x-alert title='Error!'>{{ $error }}</x-alert>
	@endif
</div>
