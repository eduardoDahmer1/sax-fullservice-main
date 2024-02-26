<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');

        parent::__construct();
    }

    /**
     *  Base currency datatable.
     */
    public function datatablesBase()
    {
        $datas = Currency::where('id', '=', 1);
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('name', function(Currency $data){
                if($data->is_default){ 
                    $badge = ' <span class="badge badge-pill badge-primary">'.__("Default").'</span>';
                    return __($data->name).$badge;
                } else {
                    return __($data->name);
                }
            })
            ->addColumn('action', function (Currency $data) {
                $delete = $data->id == 1 ? '' : '<a href="javascript:;" data-href="' . route('admin-currency-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                if (Session::has('admstore')) {
                    $default = Session::get('admstore')->currency_id == $data->id ? '' : '<a class="status" data-href="' . route('admin-currency-status', ['id1' => $data->id, 'id2' => 1]) . '"><i class="fa fa-dollar-sign"></i> ' . __('Set Default') . '</a>';
                } else {
                    $default = $this->storeSettings->currency_id == $data->id ? '' : '<a class="status" data-href="' . route('admin-currency-status', ['id1' => $data->id, 'id2' => 1]) . '"><i class="fa fa-dollar-sign"></i> ' . __('Set Default') . '</a>';
                }
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-currency-edit', $data->id) . '" data-header="' . __('Edit Currency') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>' . __('Edit') . '</a>
                        ' . $delete . $default . '
                    </div>
                </div>';
            })
            ->rawColumns(['action', 'name'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    /**
     * Other currencies datatable.
     */
    public function datatables()
    {
        $datas = Currency::where('id', '!=', 1)->orderBy('id');
        
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('name', function(Currency $data){
                if($data->is_default){ 
                    $badge = ' <span class="badge badge-pill badge-primary">'.__("Default").'</span>';
                    return __($data->name).$badge;
                } else {
                    return __($data->name);
                }
            })
            ->addColumn('parity', function (Currency $data) {
                $base = Currency::where('id', '=', 1)->first();
                return $base->name . '/' . $data->name;
            })
            ->addColumn('action', function (Currency $data) {
                $delete = $data->id == 1 ? '' : '
                <a href="javascript:;" data-href="' . route('admin-currency-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                    <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                </a>';
                if (Session::has('admstore')) {
                    $default = Session::get('admstore')->currency_id == $data->id ? '' : '<a class="status" data-href="' . route('admin-currency-status', ['id1' => $data->id, 'id2' => 1]) . '"><i class="fa fa-dollar-sign"></i> ' . __('Set Default') . '</a>';
                } else {
                    $default = $this->storeSettings->currency_id == $data->id ? '' : '<a class="status" data-href="' . route('admin-currency-status', ['id1' => $data->id, 'id2' => 1]) . '"><i class="fa fa-dollar-sign"></i>  ' . __('Set Default') . '</a>';
                }
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-currency-edit', $data->id) . '" data-header="' . __('Edit Currency') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        '.$delete.'
                        '.$default.'
                    </div>
                </div>';
            })
            ->rawColumns(['action', 'name'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $base = Currency::where('id', '=', 1)->first();
        return view('admin.currency.index', compact('base'));
    }

    //*** GET Request
    public function create()
    {
        return view('admin.currency.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = ['name' => 'unique:currencies','sign' => 'unique:currencies','value' => 'numeric'];
        $customs = ['name.unique' => 'This name has already been taken.','sign.unique' => 'This sign has already been taken.','value.numeric' => 'This value must be numeric'];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Currency();
        $input = $request->all();
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
        $data = Currency::findOrFail($id);
        return view('admin.currency.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = ['name' => 'unique:currencies,name,'.$id,'sign' => 'unique:currencies,sign,'.$id,'value' => 'numeric'];
        $customs = ['name.unique' => 'This name has already been taken.','sign.unique' => 'This sign has already been taken.','value.numeric' => 'This value must be numeric'];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }                
        //--- Validation Section Ends

        //--- Logic Section
        $data = Currency::findOrFail($id);
        $input = $request->all();
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends            
    }

      public function status($id1,$id2)
        {
            $data = Currency::findOrFail($id1);
            $data->is_default = $id2;
            $data->update();
            $data = Currency::where('id','!=',$id1)->update(['is_default' => 0]);
            $storeAdmin = Session::has('admstore') ? Session::get('admstore') : $this->storeSettings;
            $storeAdmin->currency_id = $id1;
            $storeAdmin->update();
            //--- Redirect Section     
            $msg = __('Data Updated Successfully.');
            return response()->json($msg);      
            //--- Redirect Section Ends  
        }

    //*** GET Request Delete
    public function destroy($id)
    {
        if($id == 1)
        {
        return "You cant't remove the main currency.";
        }
        $data = Currency::findOrFail($id);
        $usedCurrency = Generalsetting::where('currency_id', $id)->count();
        if($usedCurrency > 0)
        {
        return "You can not remove default currency of any store.";            
        }
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends     
    }

}