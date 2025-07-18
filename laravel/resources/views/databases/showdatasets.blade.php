<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database tabTitle='Datasets'/>
	</x-slot>

	<h2>Datasets</h2>
		@if(count($database->datasets)==0)
			<p>No datasets found.</p>
		@else
			<p><b>{{ count($database->datasets) }}</b> datasets found:</p>
			<table class="table-auto w-full border border-slate-399">
				<thead class="bg-gray-50">
					<tr>
						@can('create', [App\Models\Dataset::class, $database])
							<th class="border p-2 w-48">Command</th>
						@endcan
						<th class="border p-2">#</th>
						<th class="border p-2">Name</th>
						<th class="border p-2"># Datafiles</th>
						<th class="border p-2 text-left">Description</th>
						<th class="border p-2">Updated (GMT)</th>
						<th class="border p-2">Created (GMT)</th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					@foreach($database->datasets as $dataset)
						<tr>
							@can('update', [App\Models\Dataset::class, $dataset])
								<td class="border p-2">
									<x-button method="DELETE" class="inline" action="{{ route('datasets.destroy', [$dataset]) }}">Delete</x-button>
									<x-button method="GET" class="inline" action="{{ route('datasets.edit', $dataset) }}" >Edit</x-button>
								</td>
							@endcan
							<td class="border p-2 text-center">#{{ $loop->index+1}}</td>
							<td class="border p-2 text-center"><a href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->name }}</a></td>
							<td class="border p-2 text-center">{{ count($dataset->datafiles) }}</td>
							<td class="border p-2">{{ $dataset->description }}</td>
							<td class="border p-2 text-center"><small>{{ $dataset->updated_at }}</small></td>
							<td class="border p-2 text-center"><small>{{ $dataset->created_at }}</small></td>
						</tr>
					@endforeach
				</tbody>
			</table>
			
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
