<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database tabTitle='Purge Database'/>
	</x-slot>
	<livewire:database-purge :database=$database />
</x-app-layout>
