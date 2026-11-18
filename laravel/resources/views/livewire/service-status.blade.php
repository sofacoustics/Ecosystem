<div>
	@if(count($jobs) < 1)
		<x-property name="Jobs">No jobs scheduled</x-property>
	@else
		<x-property name="Jobs">{{count($jobs)}} jobs scheduled:</x-property>
		<table class="table-auto border border-slate-399">
			<thead class="bg-gray-50">
				<th>Datafile ID</th>
				<th>Datafile Name</th>
				<th> Job ID</th>
				<th>Attempts</th>
				<th>Created At</th>
				<th></th>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($jobs as $job)
					<tr>
						@if($job->datafile == null)
							<td></td>
							<td>Datafile not found!</td>
						@else	
							<td>{{ $job->datafile->id }}</td>
							<td><a href="{{ route('datafiles.show', $job->datafile->id) }}" target="_blank">{{ $job->datafile->name }}</a></td>
						@endif
						<td>{{ $job->id }}</td>
						<td>{{ $job->attempts }}</td>
						<td>{{ $job->created_at }}</td>
						<td>
							<x-button method="GET" action="{{ route('servicelog.removejob', $job->id) }}" class="inline">Remove</x-button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
	
	<hr>
	@if(count($logs_failed) < 1)
		<x-property name="Logs">All services have been resolved (even if they failed originally)</x-property>
	@else
		<x-property name="Logs">{{count($logs_failed)}} services have not been resolved:</x-property>
		<table class="table-auto border border-slate-399">
			<thead class="bg-gray-50">
				<th>Datafile ID</th>
				<th>Datafile Name</th>
				<th>Exit Code</th>
				<th>Created At</th>
				<th></th>
			</thead>	
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($logs_failed as $log)
					<tr>
						<td>{{ $log->datafile_id }}</td>
						<td><a href="{{ route('datafiles.show', $log->datafile->id) }}" target="_blank">{{ $log->datafile->name }}</a></td>
						<td>{{ $log->exit_code }}</td>
						<td>{{ $log->created_at }}</td>
						<td>
							@if($scheduled[$loop->index])
								Scheduled
							@else
								<x-button method="POST" class="inline" action="{{ route('datafiles.rerunservice', [$log->datafile]) }}">Rerun service</x-button>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</div>
