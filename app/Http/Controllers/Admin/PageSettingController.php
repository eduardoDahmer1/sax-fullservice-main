<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pagesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Validator;

class PageSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }


    protected $rules =
    [
        'best_seller_banner' => 'mimes:jpeg,jpg,png,svg,gif,webp',
        'big_save_banner' => 'mimes:jpeg,jpg,png,svg,gif,webp',
        'best_seller_banner1' => 'mimes:jpeg,jpg,png,svg,gif,webp',
        'big_save_banner1' => 'mimes:jpeg,jpg,png,svg,gif,webp',
        'banner_search1' => 'mimes:jpeg,jpg,png,svg,gif,webp',
        'banner_search2' => 'mimes:jpeg,jpg,png,svg,gif,webp',
        'banner_search3' => 'mimes:jpeg,jpg,png,svg,gif,webp',
    ];


    protected $customs =
    [
        'best_seller_banner.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
        'big_save_banner.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
        'best_seller_banner1.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
        'big_save_banner1.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
        'banner_search1.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
        'banner_search2.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
        'banner_search3.mimes' => 'Photo type must be in jpeg, jpg, png, svg, gif',
    ];


    // Page Settings All post requests will be done in this method
    public function update(Request $request)
    {
        //--- Validation Section
        $validator = Validator::make($request->all(), $this->rules, $this->customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $ps = Pagesetting::find(1);
        $data = Session::has('admstore') ? Session::get('admstore') : $ps;
        if ($data != $ps) {
            $data = $data->pagesettings;
        }
        $input = $request->all();

        if ($file = $request->file('best_seller_banner')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->best_seller_banner);
            $input['best_seller_banner'] = $name;
        }
        if ($file = $request->file('big_save_banner')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->big_save_banner);
            $input['big_save_banner'] = $name;
        }

        if ($file = $request->file('best_seller_banner1')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->best_seller_banner1);
            $input['best_seller_banner1'] = $name;
        }

        if ($file = $request->file('banner_search1')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->banner_search1);
            $input['banner_search1'] = $name;
        }

        if ($file = $request->file('banner_search2')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->banner_search2);
            $input['banner_search2'] = $name;
        }

        if ($file = $request->file('banner_search3')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->banner_search3);
            $input['banner_search3'] = $name;
        }
        if ($file = $request->file('big_save_banner1')) {
            $name = time().$data->id.$file->getClientOriginalName();
            $data->upload($name, $file, $data->big_save_banner1);
            $input['big_save_banner1'] = $name;
        }


        $data->update($input);
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }


    public function homeupdate(Request $request)
    {
        $ps = Pagesetting::where('store_id', $this->storeSettings->id)->first();
        $data = Session::has('admstore') ? Session::get('admstore') : $ps;
        if ($data != $ps) {
            $data = $data->pagesettings;
        }
        $input = $request->all();

        if ($request->slider == "") {
            $input['slider'] = 0;
        }

        if ($request->service == "") {
            $input['service'] = 0;
        }

        if ($request->featured == "") {
            $input['featured'] = 0;
        }

        if ($request->small_banner == "") {
            $input['small_banner'] = 0;
        }

        if ($request->best == "") {
            $input['best'] = 0;
        }

        if ($request->top_rated == "") {
            $input['top_rated'] = 0;
        }

        if ($request->large_banner == "") {
            $input['large_banner'] = 0;
        }

        if ($request->big == "") {
            $input['big'] = 0;
        }

        if ($request->hot_sale == "") {
            $input['hot_sale'] = 0;
        }

        if ($request->blog_posts == "") {
            $input['blog_posts'] = 0;
        }

        if ($request->reviews_store == "") {
            $input['reviews_store'] = 0;
        }

        if ($request->partners == "") {
            $input['partners'] = 0;
        }

        if ($request->bottom_small == "") {
            $input['bottom_small'] = 0;
        }

        if ($request->flash_deal == "") {
            $input['flash_deal'] = 0;
        }

        if ($request->featured_category == "") {
            $input['featured_category'] = 0;
        }

        if ($request->random_banners == "") {
            $input['random_banners'] = 0;
        }

        $data->update($input);
        $msg = __('Data Updated Successfully.');

        return response()->json($msg);
    }

    public function unlink(Request $request)
    {
        //--- Validation Section Ends
        $ps = Pagesetting::find(1);
        $data = Session::has('admstore') ? Session::get('admstore') : $ps;
        if ($data != $ps) {
            $data = $data->pagesettings;
        }
        $input = $request->all();
        if ($request->type == "best_seller_banner") {
            $input['best_seller_banner'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->best_seller_banner) && !empty($data->best_seller_banner)) {
                unlink(public_path().'/storage/images/banners/'.$data->best_seller_banner);
            }
        }
        if ($request->type == "big_save_banner") {
            $input['big_save_banner'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->big_save_banner) && !empty($data->big_save_banner)) {
                unlink(public_path().'/storage/images/banners/'.$data->big_save_banner);
            }
        }

        if ($request->type == "best_seller_banner1") {
            $input['best_seller_banner1'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->best_seller_banner1) && !empty($data->best_seller_banner1)) {
                unlink(public_path().'/storage/images/banners/'.$data->best_seller_banner1);
            }
        }
        if ($request->type == "banner_search1") {
            $input['banner_search1'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->banner_search1) && !empty($data->banner_search1)) {
                unlink(public_path().'/storage/images/banners/'.$data->banner_search1);
            }
        }
        if ($request->type == "banner_search2") {
            $input['banner_search2'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->banner_search2) && !empty($data->banner_search2)) {
                unlink(public_path().'/storage/images/banners/'.$data->banner_search2);
            }
        }
        if ($request->type == "banner_search3") {
            $input['banner_search3'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->banner_search3) && !empty($data->banner_search3)) {
                unlink(public_path().'/storage/images/banners/'.$data->banner_search3);
            }
        }
        if ($request->type == "big_save_banner1") {
            $input['big_save_banner1'] = null;
            if (file_exists(public_path().'/storage/images/banners/'.$data->big_save_banner1) && !empty($data->big_save_banner1)) {
                unlink(public_path().'/storage/images/banners/'.$data->big_save_banner1);
            }
        }

        $data->update($input);
        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }



    public function contact()
    {
        return view('admin.pagesetting.contact');
    }

    public function customize()
    {
        return view('admin.pagesetting.customize');
    }

    public function best_seller()
    {
        return view('admin.pagesetting.best_seller');
    }

    public function big_save()
    {
        return view('admin.pagesetting.big_save');
    }
}
