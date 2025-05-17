<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$creatorable" /> 
	</x-slot>
	<div>
		<livewire:creator-form :creatorable="$creator->creatorable" :creator=$creator />
	</div>
</x-app-layout>
