<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WishlistGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class WishlistGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(?User $user, WishlistGroup $wishlistGroup)
    {
        return $wishlistGroup->is_public || optional($user)->id === $wishlistGroup->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, WishlistGroup $wishlistGroup)
    {
        return $user->id === $wishlistGroup->user_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, WishlistGroup $wishlistGroup)
    {
        return $user->id === $wishlistGroup->user_id;
    }
}
