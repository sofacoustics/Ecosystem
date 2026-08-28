<div wire:poll.1s.visible>
  <p>Service Info for Admins!</p>
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
    <p>The service job is in the queue. Once it starts running, you'll see the service log output here.</p>
  @endif
</div>
