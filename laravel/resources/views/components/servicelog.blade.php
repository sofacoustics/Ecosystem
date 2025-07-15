@hasrole('admin')
<div class="{{ $adminInfo }}">
	@if($log)
		{{-- if services_log date is less than datafile modified date, then don't show it --}}
		@if($log->updated_at < $log->datafile->updated_at)
			<p>The service is running... The exit code will displayed here when the job is finished.</p>
		@else
			<p>Service: {{ $log->name }} (ID: {{ $log->service_id }})</p>
			<p>Datafile: {{ $log->datafile->name }} (ID: {{ $log->datafile_id }})</p>
			@if($log->exit_code != 0)
				<x-alert>Last service job exited with the code {{ $log->exit_code }} ({{ $log->exit_code_text }})</x-alert>
			@else
				<p>Last service job exited with the code {{ $log->exit_code }} ({{ $log->exit_code_text }})</p>
			@endif
		@endif
	@else
		<p>No service log yet!</p>
	@endif
</div>
@endhasrole
