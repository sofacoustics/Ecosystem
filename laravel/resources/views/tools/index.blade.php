<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			The SONICOM Ecosystem contains {{ @count($allTools) }} tools
		</h2>
		@can('create', \App\Models\Tool::class)
			<x-button method="GET" action="{{ route('tools.create') }}" class="inline">
				Add a New Tool
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

