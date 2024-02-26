<?php

namespace App\Http\Controllers\Vendor;

use Auth;
use Image;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();
            if($user->checkWarning())
            {
                return redirect()->route('vendor-warning',$user->verifies()->where('admin_warning','=','1')->orderBy('id','desc')->first()->id);
            }
            if(!$user->checkStatus())
            {
                return redirect()->route('vendor-verify');
            }
            return $next($request);
        });
    }

    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $prod = Product::findOrFail($id);
        if(count($prod->galleries))
        {
            $data[0] = 1;
            $data[1] = $prod->galleries;
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = null;
        $lastid = $request->product_id;
        if ($files = $request->file('gallery')){
            foreach ($files as  $key => $file){
                $val = $file->getClientOriginalExtension();
                if($val == 'jpeg'|| $val == 'jpg'|| $val == 'png'|| $val == 'svg')
                  {
                    $gallery = new Gallery;


        $img = Image::make($file->getRealPath())->resize(800, 800);
        $thumbnail = time().Str::random(8).'.jpg';
        $img->save(public_path().'/storage/images/galleries/'.$thumbnail);

                    $gallery['photo'] = $thumbnail;
                    $gallery['product_id'] = $lastid;
                    $gallery->save();
                    $data[] = $gallery;
                  }
            }
        }
        return response()->json($data);
    }

    public function destroy()
    {

        $id = $_GET['id'];
        $gal = Gallery::findOrFail($id);
            if (file_exists(public_path().'/storage/images/galleries/'.$gal->photo)) {
                unlink(public_path().'/storage/images/galleries/'.$gal->photo);
            }
        $gal->delete();

    }

}
