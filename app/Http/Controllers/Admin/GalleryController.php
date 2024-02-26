<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use App\Models\Product;
use App\Models\Gallery360;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $prod = Product::findOrFail($id);

        if (count($prod->galleries)) {
            $data[0] = 1;
            $data[1] = $prod->galleries;
        }

        if ($this->storeSettings->ftp_folder) {
            $ftp_path = public_path('storage/images/ftp/' . $this->storeSettings->ftp_folder . $prod->ref_code_int . '/');
            $ftp_gallery=[];
            if (File::exists($ftp_path)) {
                $files = scandir($ftp_path);
                $extensions = array('.jpg','.jpeg','.gif','.png');
                foreach ($files as $file) {
                    $file_extension = strtolower(strrchr($file, '.'));
                    if (in_array($file_extension, $extensions) === true) {
                        $ftp_gallery[] = asset('storage/images/ftp/' . $this->storeSettings->ftp_folder . $prod->ref_code_int . '/' . $file);
                    }
                }
                $data[0] = 1;
                $data[2] = $ftp_gallery;
            }
        }

        return response()->json($data);
    }

    public function show360()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $prod = Product::findOrFail($id);
        if (count($prod->galleries360)) {
            $data[0] = 1;
            $data[1] = $prod->galleries360;
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = null;
        $lastid = $request->product_id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                $val = $file->getClientOriginalExtension();
                if ($val == 'jpeg'|| $val == 'jpg'|| $val == 'png'|| $val == 'svg') {
                    $gallery = new Gallery;


                    $img = Image::make($file->getRealPath())->resize(800, 800);
                    $thumbnail = time().Str::random(8).".".$file->getClientOriginalExtension();
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

    public function store360(Request $request)
    {
        $data = null;
        $lastid = $request->product_id;
        if ($files = $request->file('gallery360')) {
            foreach ($files as  $key => $file) {
                $val = $file->getClientOriginalExtension();
                if ($val == 'jpeg'|| $val == 'jpg'|| $val == 'png'|| $val == 'svg') {
                    $gallery = new Gallery360;


                    $img = Image::make($file->getRealPath())->resize(800, 800);
                    $thumbnail = time().Str::random(8).'.jpg';
                    $img->save(public_path().'/storage/images/galleries360/'.$thumbnail);

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

    public function destroy360()
    {
        $id = $_GET['id'];
        $gal = Gallery360::findOrFail($id);
        if (file_exists(public_path().'/storage/images/galleries360/'.$gal->photo)) {
            unlink(public_path().'/storage/images/galleries360/'.$gal->photo);
        }
        $gal->delete();
    }
}
