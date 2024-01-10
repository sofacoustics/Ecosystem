{{--https://stackoverflow.com/questions/38665770/recursive-display-of-data-with-blade-laravel --}}
{{-- https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships --}}
<li class="mr-1">
    <a class="inline-block py-2 px-4 text-blue-500 hover:text-blue-800 font-semibold" href="{{ $menuItem->url() }}">{{ $menuItem->title }}</a>
    @if($menuItem->children->count())
        @if (\Request::routeIs($menuItem->route) == false)
          {{-- These submenus belong to the wrong route --}}
        @elseif (!Auth::check() && $subMenuItem->authenticated == 1)
          {{-- This item is restricted to authenticated users --}}
          c
        @else
          @if (!auth()->user()->hasRole('admin') && $subMenuItem->authenticated == 2)
            {{-- This item is restricted to users with the 'admin' role --}}
            d
          @else
            <ul>
                @foreach($menuItem->children as $child)
                    <x-menu-item :menuItem="$child"/>
                @endforeach
            </ul>
          @endif
        @endif
    @endif
</li>