<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database tabTitle='Download'/>
	</x-slot>
	<h3>Database ID: {{ $database->id }}
	<h3>Datafile list:</h3>
	<p>
		JSON format: 
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
	

</x-app-layout>
