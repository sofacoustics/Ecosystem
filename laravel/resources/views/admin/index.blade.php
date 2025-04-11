<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin
        </h2>
    </x-slot>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    {{-- sub-menus --}}
    {{-- https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships/ --}}
    <ul class="flex border-b">
        @foreach(\App\Models\MenuItem::where('parent_id', \App\Models\MenuItem::where('route', 'admin')->first()->id)->get() as $menuItem)
            <x-menu-item :menuItem="$menuItem"/>
        @endforeach
    </ul>

    jw:todo the admin.index view
</x-app-layout>