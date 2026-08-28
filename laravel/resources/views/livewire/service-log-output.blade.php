<div wire:poll.1s.visible>
	@if(isset($servicelog))
		<p>ServiceLog ID: {{ $servicelog->id }}</p>
		<p>Service: {{ $servicelog->name }} (ID: {{ $servicelog->service_id }}, Timeout: {{ $servicelog->service->timeout }})</p>
		<p>Execution started: {{ $servicelog->created_at }}, Execution time: {{ $this->formattedValue }} s</p>
		<p>Exit code: {{ $servicelog->exit_code }}</p>
		<p>Exit message: {{ $servicelog->exit_code_text }}</p>
		<p>StdOut:</p>
		<pre class="text-xs"><code>{{ $servicelog->stdout }}</code></pre>
		<p>StdErr:</p>
		<pre class="text-xs">{{ $servicelog->stderr }}</pre>
	@else
		<p>No service log yet: maybe it's in the queue!</p>
	@endif
</div>
