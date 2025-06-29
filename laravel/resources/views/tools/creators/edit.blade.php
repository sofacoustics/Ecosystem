<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$creatorable" />
	</x-slot>
	@can('update', $creatorable)
		<livewire:creator-form :creatorable="$creator->creatorable" :creator=$creator />
	@else
		You can not edit this creator because you do not own the corresponding tool. 
	@endcan
</x-app-layout>
