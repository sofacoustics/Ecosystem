{{--
    Edit a comment
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$comment->database" />
    </x-slot>
    <div>

			<livewire:comment-form :database="$comment->database" :comment=$comment />
    </div>
</x-app-layout>
