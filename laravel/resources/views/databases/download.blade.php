<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database tabTitle='Download'/>
	</x-slot>
	<h3>Database download:</h3>
	<li><b>ID:</b> {{ $database->id }}</li>
	<li><b>URL:</b> 
		<a href="{{ route('databases.show', ['database' => $database->id]) }}">
		{{ route('databases.show', ['database' => $database->id]) }}</a>
		<img id="copyURL" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 1.5em; display: inline-block;">
		<input type="text" id="textURL" value="{{ route('databases.show', ['database' => $database->id]) }}" class="hidden">
	</li>
	<li><b>Datafile list (JSON format):</b>: 
		<a href="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}">
		{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}
		</a>
		<img id="copyJSON" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 1.5em; display: inline-block;">
		<input type="text" id="textJSON" value="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}" class="hidden">
	</li>
	
	<li><b>Clients (Datafile list and download):</b>  
		<a href="{{ asset('clients/matlab/databaseDownload.m') }}" download>Matlab</a> 
		<a href="{{ asset('clients/octave/databaseDownload.m') }}" download>Octave</a> 
		<a href="{{ asset('clients/python/databaseDownload.py') }}" download>Python</a> 
	</li>
	
	<script>
		document.getElementById('copyJSON').addEventListener('click', function() {
				// Get the text from the input field
				var textToCopy = document.getElementById('textJSON').value;

				// Use the Clipboard API to copy the text
				navigator.clipboard.writeText(textToCopy).then(function() {
						alert(textToCopy + '\ncopied to the clipboard...');
				}).catch(function(err) {
						console.error('Failed to copy text: ', err);
						alert('Failed to copy text. Please copy manually.'); // Inform the user
				});
		});

		document.getElementById('copyURL').addEventListener('click', function() {
				// Get the text from the input field
				var textToCopy = document.getElementById('textURL').value;

				// Use the Clipboard API to copy the text
				navigator.clipboard.writeText(textToCopy).then(function() {
						alert(textToCopy + '\ncopied to the clipboard...');
				}).catch(function(err) {
						console.error('Failed to copy text: ', err);
						alert('Failed to copy text. Please copy manually.'); // Inform the user
				});
		});

	</script>
	

</x-app-layout>
