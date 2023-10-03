{{--https://stackoverflow.com/questions/38665770/recursive-display-of-data-with-blade-laravel --}}
{{-- https://coding-lesson.com/creating-dynamic-nested-menus-in-laravel-with-relationships --}}
<li>
    <a href="{{ $menuItem->url }}">{{ $menuItem->title }}</a>
    @if($menuItem->children->count())
        <ul>
            @foreach($menuItem->children as $child)
                <x-menu-item :menuItem="$child"/>
            @endforeach
        </ul>
    @endif
</li>