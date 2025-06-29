<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$keywordable" />
	</x-slot>
	@can('update', $keywordable)
		<livewire:keyword-form :keywordable="$keyword->keywordable" :keyword=$keyword />
	@else
		You can not edit this keyword because you do not own the corresponding tool. 
	@endcan
</x-app-layout>
