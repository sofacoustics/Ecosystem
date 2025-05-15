<x-app-layout>
	<x-slot name="header">
			<x-tool.header :tool=$tool />
	</x-slot>
	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@can('update', $tool)
		<livewire:tool-form :tool=$tool />
	@else
		<p>BUG: You may not edit this tool! You should not be able to access this page. Please report this to the webmaster.</p>
	@endcan
	@guest
		<p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
	@endguest


</x-app-layout>
