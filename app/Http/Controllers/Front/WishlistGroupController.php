<?php

namespace App\Http\Controllers\Front;

use App\Models\WishlistGroup;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.wishlists.index', [
            'wishlistsGroup' => WishlistGroup::currentUser(auth()->user())->with('wishlists', 'wishlists.product')->paginate(20),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['string', 'required']]);
        $data['user_id'] = auth()->user()->id;
        WishlistGroup::create($data);

        return to_route('user-wishlists');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Http\Response
     */
    public function show(WishlistGroup $wishlistGroup)
    {
        $this->authorize('view', $wishlistGroup);

        $wishlistsGroup = collect();
        if (Auth::check() && auth()->user()->id === $wishlistGroup->user_id) {
            $wishlistsGroup = WishlistGroup::currentUser(auth()->user())->get();
        }

        return view('front.wishlists.show', [
            'wishlistGroup' => $wishlistGroup,
            'wishlistsGroup' =>  $wishlistsGroup,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(WishlistGroup $wishlistGroup)
    {
        $this->authorize('delete', $wishlistGroup);
        Wishlist::where('wishlist_group_id', $wishlistGroup->id)->delete();

        $wishlistGroup->delete();
        return to_route('user-wishlists');
    }

        /**
     * Display the specified resource.
     *
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Http\Response
     */
    public function changePrivacity(WishlistGroup $wishlistGroup)
    {
        $this->authorize('update', $wishlistGroup);
        $wishlistGroup->is_public = !$wishlistGroup->is_public;
        $wishlistGroup->save();

        return response()->json([
            "success" => __("Successfully"),
        ]);
    }
}
