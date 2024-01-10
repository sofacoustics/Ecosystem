<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/*
    jw:note For submenus:
    jw:note     This component and its view assume that the parent item's routes are 'named'
    jw:note     and that the named route is used in the the menu_items table's parent's 'route' column.
*/
class MenuItem extends Component
{
    public $menuItem;
    /**
     * Create a new component instance.
     */
    public function __construct($menuItem)
    {
        $this->menuItem = $menuItem;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.menu-item');
    }
}
