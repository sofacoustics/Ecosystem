<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database tabTitle='Download'/>
	</x-slot>
	<h3>Database download resources:</h3>
	<ul class="p-2 list-disc list-outside">
		<li><b>Ecosystem ID:</b> {{ $database->id }}</li>
		<li><b>URL to the Database:</b> 
			<a href="{{ route('databases.show', ['database' => $database->id]) }}">
			{{ route('databases.show', ['database' => $database->id]) }}</a>
			<img id="copyURL" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 1.5em; display: inline-block;">
			<input type="text" id="textURL" value="{{ route('databases.show', ['database' => $database->id]) }}" class="hidden">
		</li>
		@if($database->doi)
			<li><b>URL to a TAR file with all datafiles:</b>
				@if($database->radar_status==4)
					<a href="https://datathek.oeaw.ac.at/radar-backend/archives/{{ substr($database->doi,9) }}/versions/1/content">
						https://datathek.oeaw.ac.at/radar-backend/archives/{{ substr($database->doi,9) }}/versions/1/content
					</a>
				@else 
					https://datathek.oeaw.ac.at/radar-backend/archives/{{ substr($database->doi,9) }}/versions/1/content (The link will be active after persistent publication of this Database)
				@endif
			</li>
		@endif
		<li><b>List of all Datafiles in this Database (JSON format):</b> 
			<a href="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}">
			{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}
			</a>
			<img id="copyJSON" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 1.5em; display: inline-block;">
			<input type="text" id="textJSON" value="{{ route('databases.download', ['database' => $database->id, 'type' => 'json']) }}" class="hidden">
		</li>
<style>
	code {
			padding: 2px 4px;
			font-size: 90%;
			color: #c7254e; /* A common color for inline code */
			background-color: #FFFFFF;
			border-radius: 4px;
			white-space: nowrap; /* Prevents long code from wrapping */
	}
</style>
		
		<li><b>Remote clients:</b>
			<ul class="list-disc list-outside ps-5 mt-2 space-y-1">
				<li><a href="{{ asset('clients/matlab/databaseDownload.m') }}" download>
					<img src="{{ asset('images/Matlab_Logo.png') }}" alt="Matlab" title="Example code for Matlab" style="height: 2em; display: inline-block;"/></a>
				Start with: <code>databaseDownload('destination_directory',{{ $database->id }});</code></li>
				<li><a href="{{ asset('clients/octave/databaseDownload.m') }}" download>
					<img src="{{ asset('images/Gnu-octave-logo.svg') }}" alt="Octave" title="Example code for Octave" style="height: 2em; display: inline-block;"/></a>
				Start with: <code>databaseDownload('destination_directory',{{ $database->id }});</code></li>
				</li> 
				<li><a href="{{ asset('clients/python/databaseDownload.py') }}" download>
					<img src="{{ asset('images/python-logo-only.png') }}" alt="Python" title="Example code for Python" style="height: 2em; display: inline-block;"/></a>
				Start with: <code> python databaseDownload.py "destination_directory" -id {{ $database->id }}</code></li>
				</li>
				<li>The code above retrieves the list of Datafiles in this Database and downloads them to the local directory <code>destination_directory</code>. 
			</ul>
		</li>
	</ul>
	
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
