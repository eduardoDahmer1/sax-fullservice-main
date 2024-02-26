<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategoryGallery;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CategoryGalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    public function open($catid)
    {
        $data = Category::findOrFail($catid);
        return view('admin.category.gallery', compact('data'));
    }

    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $cat = Category::findOrFail($id);
        if ($cat != '') {
            if (count($cat->categories_galleries)) {
                $data[0] = 1;
                $data[1] = $cat->categories_galleries;
            }
        }
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = null;
        $lastid = $request->category_id;
        if ($files = $request->file('gallery')) {
            foreach ($files as  $key => $file) {
                $val = $file->getClientOriginalExtension();
                if ($val == 'jpeg'|| $val == 'jpg'|| $val == 'png'|| $val == 'svg') {
                    $gallery = new CategoryGallery;

                    $img = Image::make($file->getRealPath())->resize(800, 800);
                    $thumb = Image::make($file->getRealPath())->resize(285, 285);

                    $thumbnail = time().Str::random(8).'.jpg';

                    $img->save(public_path().'/storage/images/galleries/'.$thumbnail);
                    $thumb->save(public_path().'/storage/images/thumbnails/'.$thumbnail);

                    $gallery['customizable_gallery'] = $thumbnail;
                    $gallery['category_id'] = $lastid;
                    $gallery['thumbnail'] = $thumbnail;
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
        $gal = CategoryGallery::findOrFail($id);
        if (file_exists(public_path().'/storage/images/galleries/'.$gal->customizable_gallery)) {
            unlink(public_path().'/storage/images/galleries/'.$gal->customizable_gallery);
            unlink(public_path().'/storage/images/thumbnails/'.$gal->thumbnail);
        }
        $gal->delete();
    }
}
