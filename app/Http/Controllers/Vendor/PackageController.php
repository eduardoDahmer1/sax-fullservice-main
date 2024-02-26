<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Package;
use Datatables;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Illuminate\Support\Facades\Session;
use DB;

class PackageController extends Controller
{
    public $global_language;

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

    //*** JSON Request
    public function datatables()
    {
        $user = Auth::user()->id;
        $datas =  Package::where('user_id', '=', $user)->orderBy('id','desc');
         
        return Datatables::of($datas)
        ->addColumn('action', function (Package $data) {
            return '
            <div class="godropdown">
                <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                <div class="action-list"><a data-href="' . route('vendor-package-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                    <a href="javascript:;" data-href="' . route('vendor-package-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                </div>
            </div>';
        })
        ->editColumn('price', function(Package $data) {
            $sign = Currency::find($this->storeSettings->currency_id);
            $price = $sign->sign.$data->price;
            return  $price;
        })
        ->rawColumns(['action'])
        ->toJson(); 
    }

    //*** GET Request
    public function index()
    {
        return view('vendor.package.index');
    }

    //*** GET Request
    public function create()
    {
        $sign = Currency::find($this->storeSettings->currency_id);
        return view('vendor.package.create',compact('sign'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = ['title' => 'unique:packages'];
        $customs = ['title.unique' => 'This title has already been taken.'];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Package();
        $input = $this->removeEmptyTranslations($request->all());
        $input['user_id'] = Auth::user()->id;
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($id)
    {
        $sign = Currency::find($this->storeSettings->currency_id);
        $data = Package::findOrFail($id);
        return view('vendor.package.edit',compact('data','sign'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = ['title' => 'unique:packages'];
        $customs = ['title.unique' => 'This title has already been taken.'];

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }      
        //--- Validation Section Ends

        //--- Logic Section
        $data = Package::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['title', 'subtitle']);
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends            
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Package::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }
}