<x-app-layout>
	<div class="alert alert-danger">
		<p>{{ $exception->getMessage() ?: 'You are not authorized to access this page.' }}</p>
	</div>
</x-app-layout>
