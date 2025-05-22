<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database :tabTitle=$tabTitle />
	</x-slot>

	@if(isset($tabTitle))
		<h2>{{ $tabTitle }}</h2>
	@endif
	@section('tabTitle', " | " . $tabTitle)
	<p>ID: @if(isset($radar['id'])){{ $radar['id'] }}@endif</p>
	<p>State: @if(isset($radar['state'])){{ $radar['state'] }}@endif</p>
	<p>DOI: @if(isset($radar['descriptiveMetadata']['identifier']['value'])){{ $radar['descriptiveMetadata']['identifier']['value'] }}@endif</p>
	<p>Size: @if(isset($radar['technicalMetadata']['size'])){{ $radar['technicalMetadata']['size'] }}@endif</p>

	@can('update', $database)
		<h2>Actions</h2>

		@if(!isset($radar['id']))
			<x-button
				method="POST"
				action="{{ route('databases.radarcreate', ['database' => $database]) }}"
				class="inline">
				Create Dataset
			</x-button>
		@endif
		@if($radar['state'] == 'PENDING')
			<x-button
				method="POST"
				action="{{ route('databases.startreview', ['database' => $database]) }}"
				class="inline">
				Start Review
			</x-button>
		@endif
		@if($radar['state'] == 'REVIEW')
			<x-button
				method="POST"
				action="{{ route('databases.endreview', ['database' => $database]) }}"
				class="inline">
				End Review
			</x-button>
		@endif
	@endcan

	<h2>Notifications</h2>
	@if(session('message'))
		<div class="alert alert-success">
			{{ session('message') }}
		</div>
	@endif
	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger">{{ session('error') }}</div>
	@endif
	@if(session('api-status'))
		<div class="alert">Last API call status: {{ session('api-status') }}</div>
	@endif

</x-app-layout>
