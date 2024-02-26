<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistGroup extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User  $user
     * @return void
     */
    public function scopeCurrentUser($query, User $user)
    {
        $query->where('user_id', $user->id);
    }
}
