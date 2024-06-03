<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Datafile;

class img extends Component
{
    //jw:note This is one way to set the default values, I thought!
    //jw:note However, I couldn't get it to work. Using @props in blade file instead.
    public $attributes = [
        'caption' => 'Empty caption',
        'class' => '',
        'asset' => '',
    ];

    /**
     * Create a new component instance.
     */
    
    public function __construct(
        public string $asset,
        public string $class,
        public string $caption,
    ) { }

    /**
     * Get the view / contents that represent the component.
     */
    //public function render(): View|Closure|string
    public function render()
    {
        return view('components.img');
    }
}
