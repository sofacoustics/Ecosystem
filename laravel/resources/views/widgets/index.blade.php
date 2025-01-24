<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Widgets</h2>
    </x-slot>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
		
    <p>The SONICOM Ecosystem offers the following widgets to process the data online:</p>
    <table class="table-auto border border-slate-399">
        <tr class="text-left">
            <th>Name</th>
            <th>Description</th>
						<th>Script Name</th>
        </tr>
    @foreach ($allWidgets as $widget)
        <tr class="border">
						<td class="border p-2"><a class="btn btn-primary" href="{{ route('widgets.show', $widget->id) }}">{{ $widget->name }}</a></td>
            <td class="border p-2"><a class="btn btn-primary" href="{{ route('widgets.show', $widget->id) }}">{{ $widget->description}}</a></td>
						<td class="border p-2"><a class="btn btn-primary" href="{{ route('widgets.show', $widget->id) }}">{{ $widget->scriptname}}</a></td>
        </tr>
    @endforeach
		</table>


{{-- <livewire:create-database /> --}}
</x-app-layout>
