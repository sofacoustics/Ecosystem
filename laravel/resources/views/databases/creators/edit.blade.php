{{--
    Edit creators
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$creator->database" />
    </x-slot>
    <div>
		<p>
                @if($creator)
                    <h3>Edit a creator:</h3>
                @else
										<h3>Add a new creator:</h3>
                @endif
            </p>
		
			<livewire:creator-form :database="$creator->database" :creator=$creator />
    </div>
</x-app-layout>
