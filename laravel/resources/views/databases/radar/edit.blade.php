<x-app-layout>
    <x-slot name="header">
				PM: IS THIS USED AT ALL???
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database: {{ $database->title }}<br>
        </h2>
        User: {{ \App\Models\User::find($database->user_id)->name }}
        @auth
        @endauth<br>
        Description: {{-- $database->descriptiongeneral --}}
    </x-slot>
    <h3>RADAR Metadata</h3>
    @auth
        <livewire:radar.dataset :database="$database" />
    @endauth
    <a type="submit" href="{{url()->previous()}}" class="btn btn-default">Cancel</a>
</x-app-layout>

