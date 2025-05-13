<x-app-layout>
	<x-slot name="header">
			<x-database.header :database="$database" />
	</x-slot>
	
	<h3>New comment:</h3>
	<livewire:comment-form :commentable="$database" />
</x-app-layout>
