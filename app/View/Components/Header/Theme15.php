<?php

namespace App\View\Components\Header;

use App\Models\Category;
use Illuminate\View\Component;

class Theme15 extends Component
{
    public $nav_categories;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->nav_categories = Category::with(['products' => function ($query) {
            $query->where('show_in_navbar', 1);
        }])->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('front.themes.theme-15.components.header');
    }
}
