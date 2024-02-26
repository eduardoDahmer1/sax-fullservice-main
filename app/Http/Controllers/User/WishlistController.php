<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\WishlistGroup;
use Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function wishlists(Request $request)
    {
        $qty = '';
        $sort = '';
        $user = Auth::guard('web')->user();
        // Search By Sort
        if(!empty($request->sort))
        {
        $sort = $request->sort;
        $wishes = Wishlist::where('user_id','=',$user->id)->pluck('product_id');
        if($sort == "date_desc")
        {
        $wishlists = Product::where('status','=',1)->whereIn('id',$wishes)->orderBy('id','desc')->paginate(8);
        }
        else if($sort == "date_asc")
        {
        $wishlists = Product::where('status','=',1)->whereIn('id',$wishes)->paginate(8);
        }
        else if($sort == "price_asc")
        {
        $wishlists = Product::where('status','=',1)->whereIn('id',$wishes)->orderBy('price','asc')->paginate(8);
        }
        else if($sort == "price_desc")
        {
        $wishlists = Product::where('status','=',1)->whereIn('id',$wishes)->orderBy('price','desc')->paginate(8);
        }
        else if($sort == "availability"){
        $wishlists = Product::whereRaw('stock != 0')->whereIn('id',$wishes)->orderBy('stock', 'DESC')->paginate(8);
        }
        if($request->ajax())
        {
            return view('front.pagination.wishlist',compact('user','wishlists','sort', 'qty'));
        }
        return view('user.wishlist',compact('user','wishlists','sort', 'qty'));
        }
        
        $best_products = Product::where('best', 1)->get();
        
        $wishlists = Wishlist::where('user_id','=',$user->id)->paginate(8);
        
        if($request->ajax())
        {
            return view('front.pagination.wishlist',compact('user','wishlists','sort', 'qty'));
        }
        return view('user.wishlist',compact('user','wishlists','sort', 'qty', 'best_products'));
    }

    public function addwish($id, $group)
    {
        if (!$group) {
            return response()->json(['error' => __('You need choose some wishlist')]);
        }

        $user = Auth::guard('web')->user();
        $wishlistGroup = WishlistGroup::find($group);

        if (!$wishlistGroup) {
            $wishlistGroup = WishlistGroup::create(['name' => $group, 'user_id' =>  $user->id]);
        }

        if ($wishlistGroup->user_id != $user->id) {
            return response()->json(['error' => __('You are trying to insert into a list that isn\'t yours')]);
        }

        $data[0] = 0;
        $data["success"] = __("Successfully Added To Wishlist");
        $data["error"] = __("Already Added To Wishlist");
        $ck = Wishlist::where('user_id','=',$user->id)
            ->where('product_id','=',$id)->where('wishlist_group_id', $wishlistGroup->id)->count();

        if($ck > 0) {
            return response()->json($data);
        }

        $wish = new Wishlist();
        $wish->user_id = $user->id;
        $wish->product_id = $id;
        $wish->wishlist_group_id = $wishlistGroup->id;
        $wish->save();
        $data[0] = 1;
        $data[1] = count($user->wishlists);
        return response()->json($data);
    }

    public function removewish($id)
    {
        $user = Auth::guard('web')->user();
        $wish = Wishlist::findOrFail($id);
        $wish->delete();
        $data[0] = 1;
        $data[1] = count($user->wishlists);
        $data["success"] = __("Successfully Removed From The Wishlist");
        return response()->json($data);
    }

}
