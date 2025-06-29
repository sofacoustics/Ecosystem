<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$creatorable" /> 
	</x-slot>
	@can('update', $creatorable)
		<livewire:creator-form :creatorable="$creator->creatorable" :creator=$creator />
	@else
		You can not edit this creator because you do not own the corresponding database. 
	@endcan
</x-app-layout>
