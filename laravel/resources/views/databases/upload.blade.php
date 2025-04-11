<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database />
	</x-slot>
	<h3>Bulk upload</h3>
	<livewire:database-upload :database=$database />
</x-app-layout>
