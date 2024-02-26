<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomProduct;
use App\Models\Generalsetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomProductController extends Controller
{
    public function logoUpload(Request $request){
         //--- Validation Section
         $rules = [
            'customizable_logo' => 'mimes:jpeg,jpg,png,svg,webp',
         ];
        $customs = [
            'customizable_logo.mimes' => __('Image type is invalid.'),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            $msg = __('Image type is invalid.');
            return response()->json(['success' => false, 'message' => $msg]);
        } else{
            if ($file = $request->file('customizable_logo')){
                $name = $file->getClientOriginalName();
                $file->move('storage/images/custom-logo/', $name);
                $msg = __('New Data Added Successfully.');
                return response()->json(['success' => true, 'message' => $msg]);
            }
        }

    }

    public function downloadLogo($file)
    {
        return response()->download('storage/images/custom-logo/' . $file);
    }
}
