<div>
	@if(count($jobs) < 1)
		<x-property name="Service jobs">No service jobs scheduled</x-property>
	@else
		<x-property name="Service jobs">{{count($jobs)}} service jobs scheduled:</x-property>
		<x-button method="DELETE" action="{{ route('servicelog.removealljobs') }}" class="inline"
			confirm="Are you sure to remove all {{ count($jobs) }} service jobs? Other jobs (e.g. RADAR uploads) are not removed.">
			Remove all service jobs
		</x-button>
		<table class="table-auto border border-slate-300">
			<thead class="bg-gray-50">
				<tr>
					<th>Datafile ID</th>
					<th>Datafile Name</th>
					<th>Job ID</th>
					<th>Queue</th>
					<th>Attempts</th>
					<th>Created At</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($jobs as $job)
					<tr>
						<td>{{ $job['datafile_id'] }}</td>
						<td>
							@if($job['datafile_name'] === null)
								Datafile not found!
							@else
								<a href="{{ route('datafiles.show', $job['datafile_id']) }}" target="_blank">{{ $job['datafile_name'] }}</a>
							@endif
						</td>
						<td>{{ $job['id'] }}</td>
						<td>{{ $job['queue'] }}</td>
						<td>{{ $job['attempts'] }}</td>
						<td>{{ $job['created_at'] }}</td>
						<td>
							<x-button method="GET" action="{{ route('servicelog.removejob', $job['id']) }}" class="inline">Remove</x-button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	<hr>
	@if(count($jobGroups) < 1)
		<x-property name="Other jobs">No other jobs scheduled</x-property>
	@else
		<x-property name="Other jobs">{{ array_sum(array_column($jobGroups, 'count')) }} other jobs scheduled (e.g. RADAR uploads), grouped by type, queue and database:</x-property>
		<table class="table-auto border border-slate-300">
			<thead class="bg-gray-50">
				<tr>
					<th>Job Type</th>
					<th>Queue</th>
					<th>Database</th>
					<th>Jobs</th>
					<th>Started/Retried</th>
					<th>First Created At</th>
					<th>Last Created At</th>
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($jobGroups as $group)
					<tr>
						<td>{{ $group['type'] }}</td>
						<td>{{ $group['queue'] }}</td>
						<td>
							@if($group['database_title'] !== null)
								<a href="{{ route('databases.show', $group['database_id']) }}" target="_blank">{{ $group['database_title'] }}</a> ({{ $group['database_id'] }})
							@elseif($group['database_id'] !== null)
								{{ $group['database_id'] }} (not found)
							@endif
						</td>
						<td>{{ $group['count'] }}</td>
						<td>{{ $group['attempted'] }}</td>
						<td>{{ $group['first_created_at'] }}</td>
						<td>{{ $group['last_created_at'] }}</td>
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
		<table class="table-auto border border-slate-300">
			<thead class="bg-gray-50">
				<tr>
					<th>Datafile ID</th>
					<th>Datafile Name</th>
					<th>Exit Code</th>
					<th>Created At</th>
					<th></th>
				</tr>
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
							<x-button method="DELETE" class="inline" action="{{ route('servicelog.removelog', $log->id) }}">Remove</x-button>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</div>
