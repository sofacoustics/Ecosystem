<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$creatorable" />
	</x-slot>
	<h3>Creators</h3>
	<p>Persons or institutions responsible for creating the tool:</p>
	<x-creator.list :creatorable=$creatorable />

	<livewire:creator-form :creatorable="$creatorable" />

</x-app-layout>
