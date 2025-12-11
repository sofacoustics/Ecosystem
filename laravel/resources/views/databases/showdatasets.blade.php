<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database tabTitle='Datasets'/>
	</x-slot>

	<h2>Datasets</h2>
		@if(count($database->datasets)==0)
			<p>No datasets availabled.</p>
		@else
			<p><b>{{ count($database->datasets) }}</b> datasets available:</p>
		
			<livewire:dataset-table-filter :database=$database />
						
			<br>
			<p>
				Datafile list in JSON format: 
				<a href="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}">
				{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}
				</a>
				<img id="copyButton" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 2em; display: inline-block;">
				<input type="text" id="textToCopy" value="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}" class="hidden">
			</p>
				<script>
				document.getElementById('copyButton').addEventListener('click', function() {
						// Get the text from the input field
						var textToCopy = document.getElementById('textToCopy').value;

						// Use the Clipboard API to copy the text
						navigator.clipboard.writeText(textToCopy).then(function() {
								alert(textToCopy + '\ncopied to the clipboard...');
						}).catch(function(err) {
								console.error('Failed to copy text: ', err);
								alert('Failed to copy text. Please copy manually.'); // Inform the user
						});
				});
				</script>
			
		@endif


</x-app-layout>
