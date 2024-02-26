<?php

namespace App\View\Components;

use App\Models\WishlistGroup;
use Illuminate\View\Component;

class ModalWishlist extends Component
{
    public $wishlistGroup;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->wishlistGroup = WishlistGroup::currentUser(auth()->user())->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal-wishlist');
    }
}
