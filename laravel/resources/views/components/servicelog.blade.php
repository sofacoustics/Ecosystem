@hasrole('admin')
<div class="{{ $adminInfo }}">
	@if($log)
		<p>Service: {{ $log->name }} ({{ $log->service_id }})</p>
		<p>Exit code: {{ $log->exit_code_text }} ({{ $log->exit_code }})<br>
			last run: {{ $log->created_at }}<br>
			<b>stdout:</b> {{ $log->stdout }}<br>
			<span color="bg-red-100"><b>stderr:</b> {{ $log->stderr }}</span><br>
		</p>
	@else
		<p>No service log yet!</p>
	@endif
</div>
@else
	@if($log->exit_code != 0)
		<x-alert>Last service job exited with the code {{ $log->exit_code }} ({{ $log->exit_code_text }})</x-alert>
	@endif
@endhasrole
