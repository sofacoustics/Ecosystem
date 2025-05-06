<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database />
	</x-slot>
	<h3>Database ID: {{ $database->id }}
	<h3>Database list (JSON Format):</h3>
	<p><small> 
		<a href="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
			Download
		</a>
		
		<button id="copyButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Copy link to clipboard</button>
		<input type="text" id="textToCopy" value="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}" class="hidden">
	</small></p>
    <script>
    document.getElementById('copyButton').addEventListener('click', function() {
        // Get the text from the input field
        var textToCopy = document.getElementById('textToCopy').value;

        // Use the Clipboard API to copy the text
        navigator.clipboard.writeText(textToCopy).then(function() {
            alert('URL copied to the clipboard');
        }).catch(function(err) {
            console.error('Failed to copy text: ', err);
            alert('Failed to copy text. Please copy manually.'); // Inform the user
        });
    });
    </script>

</x-app-layout>
