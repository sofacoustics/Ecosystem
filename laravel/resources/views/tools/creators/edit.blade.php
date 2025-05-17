<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$creatorable" />
	</x-slot>
	<div>
		<livewire:creator-form :creatorable="$creator->creatorable" :creator=$creator />
	</div>
</x-app-layout>
