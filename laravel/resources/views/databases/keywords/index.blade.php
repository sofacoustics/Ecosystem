<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$keywordable" />
	</x-slot>
	
	<h3>Keywords</h3>
	<p>Keyword(s) describing the subject focus of the database:</p>
	
	@if(count($keywordable->keywords)>0)
		<table class="table-auto px-4">
		@foreach($keywordable->keywords as $keyword)
			<tr>
				@can('update', $keywordable)
					<td>
						<x-button method="GET" action="{{ route('keywords.edit', [$keyword]) }}" class="inline">
							Edit
						</x-button>
					</td>
					<td>
						@if($loop->index > 0)
							<x-button method="GET" action="{{ route('keywords.up', $keyword) }}" class="inline">
								&uarr;
							</x-button>
						@endif
					</td>
					<td>
						@if($loop->index < count($keywordable->keywords)-1)
						<x-button method="GET" action="{{ route('keywords.down', [$keyword]) }}" class="inline">
							&darr;
						</x-button>
						@endif
					</td>
				@endcan
				@can('delete', $keywordable)
					<td>
						<x-button method="DELETE" action="{{ route('keywords.destroy', [$keyword]) }}" class="inline">
							Delete
						</x-button>
					</td>
				@endcan
				<td class="px-4">
					<x-keyword.list :keyword=$keyword />
				</td>
			</tr>
		@endforeach
		</table>
	@else
			<li>No keywords defined yet.</li>
	@endif
	
	@can('update', $keywordable)
			<livewire:keyword-form :keywordable="$keywordable" />
	@endcan



</x-app-layout>
