<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database />
	</x-slot>

@can('update', $database)
	<livewire:database-visibility :database=$database />
@else
	<p>BUG: You may not edit this database! You should not be able to access this page. Please report this to the webmaster.</p>
@endcan
@guest
	<p>BUG: This page should only be accessable by authenticated users. Please report this to the webmaster. </p>
@endguest


</x-app-layout>
