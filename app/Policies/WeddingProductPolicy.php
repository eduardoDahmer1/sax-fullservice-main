<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeddingProduct;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeddingProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WeddingProduct  $weddingProduct
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, User $owner)
    {
        return $owner->id === $user?->id || $owner->is_wedding;
    }

    /**
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function buy(User $user, User $owner)
    {
        return $owner->is_wedding;
    }
}
