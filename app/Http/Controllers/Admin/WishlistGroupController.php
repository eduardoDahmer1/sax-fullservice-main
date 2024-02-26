<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WishlistGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WishlistGroupController extends Controller
{
    public function datatables()
    {
        $datas = WishlistGroup::orderBy('id', 'DESC');
        //--- Integrating This Collection Into Datatables
        return DataTables::of($datas)
            ->addColumn('action', function (WishlistGroup $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin.wishlist.show', $data->id) . '" >
                            <i class="fas fa-eye"></i> ' . __('Details') . '
                        </a>
                    </div>
                </div>';
            })
            ->addColumn('name', function (WishlistGroup $data) {
                return '<div class="ml-2">'. $data->name .'</div>';
            })
            ->addColumn('qtd', function (WishlistGroup $data) {
                return '<div class="ml-3">'. $data->wishlists()->count() .'</div>';
            })
            ->addColumn('user_name', function (WishlistGroup $data) {
                return '<div class="ml-2">'. $data->user->name .'</div>';
            })
            ->rawColumns(['action', 'qtd', 'name', 'user_name'])
            ->toJson(); //--- Returning Json Data To Client Sidedatatables
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.wishlists.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WishlistGroup  $wishlistGroup
     * @return \Illuminate\Http\Response
     */
    public function show(WishlistGroup $wishlistGroup)
    {
        $wishlistGroup->loadMissing(['wishlists', 'wishlists.product']);
        return view('admin.wishlists.show', compact('wishlistGroup'));
    }
}
