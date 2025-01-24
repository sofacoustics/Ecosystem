<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tools</h2>
    </x-slot>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
		
    <table class="table-auto border border-slate-399">
        <tr class="text-left">
            <th>Name</th>
            <th>Description</th>
        </tr>
    @foreach ($allTools as $tool)
        <tr class="border">
						<td class="border p-2"><a class="btn btn-primary" href="{{ route('tools.show', $tool->id) }}">{{ $tool->name }}</a></td>
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('tools.show', $tool->id) }}">{{ $tool->description}}</a></td>
        </tr>
    @endforeach
		</table>


{{-- <livewire:create-database /> --}}
</x-app-layout>
