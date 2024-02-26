<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Generalsetting;
use App\Models\Subcategory;
use App\Models\VendorOrder;
use App\Models\Verification;
use Auth;
use Route;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Validator;

class VendorController extends Controller
{

    public $lang;
    public function __construct()
    {

        $this->middleware('auth');

        parent::__construct();

        $this->middleware(function ($request, $next) {
            $user = Auth::guard('web')->user();

            if(Route::current()->getName() == "vendor-verify" || Route::current()->getName() == "vendor-warning" || Route::current()->getName() == "vendor-verify-submit") return $next($request);

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

    //*** GET Request
    public function index()
    {
        $user = Auth::guard('web')->user();

        $pending = VendorOrder::where('user_id','=',$user->id)->where('status','=','pending')->get();
        $processing = VendorOrder::where('user_id','=',$user->id)->where('status','=','processing')->get();
        $completed = VendorOrder::where('user_id','=',$user->id)->where('status','=','completed')->get();

        return view('vendor.index',compact('user','pending','processing','completed'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $rules = [
               'shop_image'  => 'mimes:jpeg,jpg,png,svg',
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $input = $request->all();
        $data = Auth::user();

        if ($photo = $request->file('photo'))
         {
            $name = time().$photo->getClientOriginalName();
            $photo_name = str_replace(' ', '-', $name);
            $photo->move('storage/images/users',$photo_name);
            $input['photo'] = $photo_name;
        }

        if ($file = $request->file('shop_image'))
         {
            $name = time().$file->getClientOriginalName();
            $slider_name = str_replace(' ', '-', $name);
            $file->move('storage/images/vendorbanner',$slider_name);
            $input['shop_image'] = $slider_name;
        }

        $data->update($input);
        $msg = __('Data Saved Successfully!');
        return response()->json($msg);
    }

    // Spcial Settings All post requests will be done in this method
    public function socialupdate(Request $request)
    {
        //--- Logic Section
        $input = $request->all();
        $data = Auth::user();
        if ($request->f_check == ""){
            $input['f_check'] = 0;
        }
        if ($request->t_check == ""){
            $input['t_check'] = 0;
        }

        if ($request->g_check == ""){
            $input['g_check'] = 0;
        }

        if ($request->l_check == ""){
            $input['l_check'] = 0;
        }
        $data->update($input);
        //--- Logic Section Ends
        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function profile()
    {
        $data = Auth::user();
        return view('vendor.profile',compact('data'));
    }

    //*** GET Request
    public function ship()
    {
        $gs = Generalsetting::find(1);
        if($gs->vendor_ship_info == 0) {
            return redirect()->back();
        }
        $data = Auth::user();
        return view('vendor.ship',compact('data'));
    }

    //*** GET Request
    public function banner()
    {
        $data = Auth::user();

        if(!$data->checkStatus())
        {
            return redirect()->route('vendor-verify');
        }

        return view('vendor.banner',compact('data'));
    }

      //*** POST Request */
      public function deleteImage()
      {
          $data = Auth::user();
          if($data->shop_image != null) {
              if (file_exists(public_path().'/storage/images/vendorbanner/'.$data->shop_image)) {
                  unlink(public_path().'/storage/images/vendorbanner/'.$data->shop_image);
                  $input['shop_image'] = '';
                  $data->update($input);
                  $msg = __('Image Deleted Successfully');
                  return response()->json([
                      'status'=>true,
                      'message' => $msg
                  ]);
              }
          }
      }

    public function deleteProfileImage()
    {
        $data = Auth::user();
        if($data->photo != null) {
            if (file_exists(public_path().'/storage/images/users/'.$data->photo)) {
                unlink(public_path().'/storage/images/users/'.$data->photo);
                $input['photo'] = '';
                $data->update($input);
                $msg = __('Image Deleted Successfully');
                return response()->json([
                    'status'=>true,
                    'message' => $msg
                ]);
            }
        }
    }

    //*** GET Request
    public function social()
    {
        $data = Auth::user();

        if(!$data->checkStatus())
        {
            return redirect()->route('vendor-verify');
        }

        return view('vendor.social',compact('data'));
    }

    //*** GET Request
    public function subcatload($id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory',compact('cat'));
    }

    //*** GET Request
    public function childcatload($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory',compact('subcat'));
    }

    //*** GET Request
    public function verify()
    {
        $data = Auth::user();
        if($data->checkStatus())
        {
            return redirect()->route('vendor-dashboard');
        }
        return view('vendor.verify',compact('data'));
    }

    //*** GET Request
    public function warningVerify($id)
    {
        $verify = Verification::findOrFail($id);
        $data = Auth::user();
        return view('vendor.verify',compact('data','verify'));
    }

    //*** POST Request
    public function verifysubmit(Request $request)
    {
        //--- Validation Section
        $rules = [
          'attachments.*'  => 'mimes:jpeg,jpg,png,svg|max:10000'
           ];
        $customs = [
            'attachments.*.mimes' => 'Only jpeg, jpg, png and svg images are allowed',
            'attachments.*.max' => 'Sorry! Maximum allowed size for an image is 10MB',
                   ];

        $validator = Validator::make($request->all(), $rules,$customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $data = new Verification();
        $input = $request->all();

        $input['attachments'] = '';
        $i = 0;
                if ($files = $request->file('attachments')){
                    foreach ($files as  $key => $file){
                        $name = time().$file->getClientOriginalName();
                        if($i == count($files) - 1){
                            $input['attachments'] .= $name;
                        }
                        else {
                            $input['attachments'] .= $name.',';
                        }
                        $file->move('storage/images/attachments',$name);

                    $i++;
                    }
                }
        $input['status'] = 'Pending';
        $input['user_id'] = Auth::user()->id;
        if($request->verify_id != '0')
        {
            $verify = Verification::findOrFail($request->verify_id);
            $input['admin_warning'] = 0;
            $verify->update($input);
        }
        else{

            $data->fill($input)->save();
        }

        //--- Redirect Section
        $msg = '<div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br><h3>'.__("Your Documents Submitted Successfully.").'</h3></div>';
        return response()->json($msg);
        //--- Redirect Section Ends
    }

}
