<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$creatorable" />
	</x-slot>
	<h3>Creators</h3>
	<p>Persons or institutions responsible for the content of the research data:</p>
	
	<x-creator.list :creatorable=$creatorable />

	<livewire:creator-form :creatorable="$creatorable" />

</x-app-layout>
