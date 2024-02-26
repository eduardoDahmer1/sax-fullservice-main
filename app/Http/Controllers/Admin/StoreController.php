<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Generalsetting;
use Yajra\Datatables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Pagesetting;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

/**
 * The name is Store for future uses. 
 * It references the GeneralSetting model to avoid a big refactoring of the current code.
 */
class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');

        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Generalsetting::orderBy('id');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('domain', function(Generalsetting $data){
                if($data->is_default){ 
                    $badge = ' <span class="badge badge-pill badge-primary">'.__("Default").'</span>';
                    return __($data->domain).$badge;
                } else {
                    return __($data->domain);
                }
            })
            ->addColumn('action', function (Generalsetting $data) {
                $delete = $data->id == 1 ? '' : '
                <a href="javascript:;" data-href="' . route('admin-stores-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                    <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                </a>';
                $default = "";
                if (config("features.multistore")) {
                    $default = $data->is_default == 1 ? '' : '<a class="status" data-href="' . route('admin-stores-status', ['id1' => $data->id, 'id2' => 1]) . '"><i class="fa fa-globe"></i> ' . __('Set Default') . '</a>';
                }
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-stores-edit', $data->id) . '" data-header="' . __('Edit Store') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i>' . __('Edit') . '
                        </a>' . $delete . $default . '
                    </div>
                </div>';
            })
            ->rawColumns(['action', 'domain'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.stores.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.stores.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "domain" => 'required',
            "company_document" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "domain.required" => __("Domain is required"),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $defaultStore = Generalsetting::where('is_default', 1)->firstOrFail();
        $defaultPage = Pagesetting::where('store_id', $defaultStore->id)->firstOrFail();
        $data = $defaultStore->replicateWithTranslations();
        $page = $defaultPage->replicateWithTranslations();

        $data->is_default = 0;

        $input = $this->withRequiredFields($request->all(), ['title']);

        $data->fill($input)->save();

        $page->store_id = $data->id;

        //page settings being saved
        $page->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends    
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Generalsetting::findOrFail($id);
        return view('admin.stores.edit', compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "domain" => 'required',
            "company_document" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "domain.required" => __("Domain is required"),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $input = $this->withRequiredFields($request->all(), ['title']);
        $data = Generalsetting::findOrFail($id);

        $data->update($input);

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Generalsetting::findOrFail($id);
        if ($data->is_default == 1) {
            return response()->json([
                'errors' => [__("You can't remove the default store.")]
            ]);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends     
    }

    public function status($id1, $id2)
    {
        $data = Generalsetting::findOrFail($id1);
        $data->is_default = $id2;
        $data->update();
        $data = Generalsetting::where('id', '!=', $id1)->update(['is_default' => 0]);
        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends  
    }

    public function isconfig($id, $redirect = false)
    {
        $data = Generalsetting::findOrFail($id);
        Session::put('admstore', $data);
        $this->forgetGeneralSettingsCache();
        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        if ($redirect) {
            return redirect()->back();
        }
        return response()->json($msg);
        //--- Redirect Section Ends  
    }
}
