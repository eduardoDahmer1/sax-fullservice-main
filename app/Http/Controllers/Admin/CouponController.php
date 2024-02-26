<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Brand;

class CouponController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Coupon::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('type', function (Coupon $data) {
                $type = $data->type == 0 ? __("Discount By Percentage") : __("Discount By Amount");
                return $type;
            })
            ->editColumn('price', function (Coupon $data) {
                $price = $data->type == 0 ? $data->price . '%' : $data->price;
                return $price;
            })
            ->addColumn('status', function (Coupon $data) {
                $s = $data->status == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label  class="switch"><input type="checkbox"  class="droplinks drop-sucess checkboxStatus" id="checkbox-status-'.$data->id.'" name="'.route('admin-coupon-status', ['id1' => $data->id, 'id2' => $data->status]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('action', function (Coupon $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                    <a data-href="' . route('admin-coupon-edit', $data->id) . '" data-header="' . __('Edit Coupon') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-coupon-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $data = new Coupon;
        $cats = Category::all();
        $brands = Brand::all();
        return view('admin.coupon.index', compact('cats', 'data', 'brands'));
    }

    //*** GET Request
    public function create()
    {
        $data = new Coupon;
        $cats = Category::all();
        $brands = Brand::all();
        return view('admin.coupon.create', compact('cats', 'data', 'brands'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = ['code' => 'unique:coupons'];
        $customs = ['code.unique' => __('This code has already been taken.')];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }   
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Coupon();
        $input = $request->all();
        $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
        $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
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
        $data = Coupon::findOrFail($id);
        $cats = Category::all();
        $brands = Brand::all();
        return view('admin.coupon.edit',compact('data', 'cats', 'brands'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section

        $rules = ['code' => 'unique:coupons,code,'.$id];
        $customs = ['code.unique' => __('This code has already been taken.')];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Coupon::findOrFail($id);
        $input = $request->all();
        $input['start_date'] = Carbon::parse($input['start_date'])->format('Y-m-d');
        $input['end_date'] = Carbon::parse($input['end_date'])->format('Y-m-d');
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends           
    }
      //*** GET Request Status
      public function status($id1,$id2)
        {
            $data = Coupon::findOrFail($id1);
            $data->status = $id2;
            $data->update();
        }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Coupon::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}
