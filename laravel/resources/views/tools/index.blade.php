<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			The SONICOM Ecosystem contains {{ @count($allTools) }} tools &amp; docs
		</h2>
		Tools &amp; Docs are resources that do not need the structure of a database, for example software (toolboxes, plugins), models, documentation, manuals and reports (e.g. PDF files), descriptions of physical objects, or something else.
		<br>
		@can('create', \App\Models\Tool::class)
			<x-button method="GET" action="{{ route('tools.create') }}" class="inline">
				Add a New Tool or Doc
			</x-button>
		@endcan				
	</x-slot>

	@if ($message = Session::get('success'))
			<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
			</div>
	@endif
		
	<livewire:tool-table-filter />

	<br>
	<p>
		Tool list in JSON format: <a href="{{ url()->current() . '?type=json' }}">{{ url()->current() . '?type=json' }}</a> 
		<img id="copyJSON" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 2em; display: inline-block;">
		<input type="text" id="textJSON" value="{{ url()->current() . '?type=json' }}" class="hidden">
	</p>
	
	<p>List all Tools (code for remote clients):&nbsp; 
		<a href="{{ asset('clients/matlab/toolList.m') }}" download><img 
			src="{{ asset('images/Matlab_Logo.png') }}" alt="Matlab" title="List all Tools in Matlab" style="height: 2em; display: inline-block;"
		/></a>&nbsp; &nbsp;
		<a href="{{ asset('clients/octave/toolList.m') }}" download><img 
			src="{{ asset('images/Gnu-octave-logo.svg') }}" alt="Octave" title="List all Tools in Octave" style="height: 2em; display: inline-block;"
		/></a>&nbsp; &nbsp;
		<a href="{{ asset('clients/python/toolList.py') }}" download><img 
			src="{{ asset('images/python-logo-only.png') }}" alt="Python" title="List all Tools in Python" style="height: 2em; display: inline-block;"
		/></a>
	</p>
	
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
	</script>


</x-app-layout>

