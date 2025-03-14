<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database />
	</x-slot>
<!--  <p>laravel\resources\views\databases\edit.blade.php</p> --!>
	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@can('update', $database)
		<livewire:database-form :database=$database />
	@else
		<p>BUG: You may not edit this database! You should not be able to access this page. Please report this to the webmaster.</p>
	@endcan
	@guest
		<p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
	@endguest


</x-app-layout>
