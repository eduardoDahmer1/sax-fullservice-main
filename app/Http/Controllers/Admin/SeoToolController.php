<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Seotool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\ProductClick;
use App\Models\Product;

class SeoToolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    public function analytics()
    {
        $tool = Seotool::find(1);
        return view('admin.seotool.googleanalytics', compact('tool'));
    }

    public function analyticsupdate(Request $request)
    {
        $tool = Seotool::findOrFail(1);
        $tool->update($request->all());
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function tagmanager()
    {
        $tool = Seotool::find(1);
        return view('admin.seotool.tagmanager', compact('tool'));
    }

    public function tagmanagerupdate(Request $request)
    {
        $tool = Seotool::findOrFail(1);
        $tool->update($request->all());
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function keywords()
    {
        $tool = Seotool::find(1);
        return view('admin.seotool.meta-keywords', compact('tool'));
    }

    public function keywordsupdate(Request $request)
    {
        $tool = Seotool::findOrFail(1);
        $tool->update($request->all());
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
    }

    public function popular($id)
    {
        $expDate = Carbon::now()->subDays($id);

        $productss = ProductClick::selectRaw('count(product_id) as product_count, product_id')->whereDate('date', '>', $expDate)->groupBy('product_id')->limit(20)->get()->sortByDesc('product_count');

        $val = $id;
        return view('admin.seotool.popular', compact('val', 'productss'));
    }

    public function rated(){
        if(!config("features.marketplace")) return redirect()->route('admin.dashboard')->with('unsuccess', __('Sorry the page does not exist.'));
        $productss = Product::whereHas('ratings')
        ->join('ratings', 'ratings.product_id', '=', 'products.id')
        ->selectRaw('count(*) as ratings_qty, products.*, ratings.rating, ratings.review')
        ->groupBy('products.id')
        ->orderBy('ratings_qty', 'DESC')
        ->get();
        return view('admin.seotool.rated', compact('productss')); 
    }

    public function fbpixel()
    {
        $tool = Seotool::find(1);
        return view('admin.seotool.facebookpixel', compact('tool'));
    }

    public function fbpixelupdate(Request $request){       
        $tool = Seotool::findOrFail(1);
        $tool->update($request->all());
        $msg = __('Data Updated Successfully.');   
        return response()->json($msg);       
    }
}
