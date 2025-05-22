<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database tabTitle='Bulk Upload'/>
	</x-slot>
	<livewire:database-upload :database=$database />
</x-app-layout>
