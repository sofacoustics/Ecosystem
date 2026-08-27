<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Server Error
        </h2>
    </x-slot>

	<p>There has been a '500' error. The website admins have been informed and will try and fix this ASAP.</p>
	@if(!empty($errorId))
 		<p>If you do log this issue on github, please use the following reference code: <strong>{{ $errorId }}</strong></p>
	@endif
</x-app-layout>
