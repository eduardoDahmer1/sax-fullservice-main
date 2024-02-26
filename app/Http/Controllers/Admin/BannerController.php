<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Generalsetting;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables($type)
    {
        $datas = Banner::where('type', '=', $type)->orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('photo', function (Banner $data) {
                $photo = $data->photo ? url('storage/images/banners/' . $data->photo) : url('assets/images/noimage.png');
                return '<img src="' . $photo . '" alt="Image">';
            })
            ->editColumn('store', function (Banner $data) {
                foreach ($data->stores as $store) {
                    return $store->domain;
                }
            })
            ->editColumn('updated_at', function (Banner $data) {
                setlocale(LC_ALL, \App\Helpers\Helper::strLocaleVariations($this->lang->locale));
                return $data->updated_at->formatLocalized('%d/%m/%Y, %T');
            })
            ->addColumn('action', function (Banner $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-sb-edit', $data->id) . '" data-header="' . __('Edit Banner') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                        <a href="javascript:;" data-href="' . route('admin-sb-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
                    </div>
                </div>';
            })
            ->filterColumn('store_id', function ($query, $keyword) {
                $this->store_id = $keyword;
                $query->whereHas('stores', function ($query) {
                    $query->where('store_id', $this->store_id);
                });
            })
            ->rawColumns(['photo', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.index', compact('storesList'));
    }

    //*** GET Request
    public function large()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.large', compact('storesList'));
    }

    //*** GET Request
    public function bottom()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.bottom', compact('storesList'));
    }

    //*** GET Request
    public function create()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.create', compact('storesList'));
    }

    //*** GET Request
    public function largecreate()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.largecreate', compact('storesList'));
    }

    //*** GET Request
    public function thumbnail()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.thumbnail', compact('storesList'));
    }

    public function thumbnailcreate()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.thumbnailcreate', compact('storesList'));
    }

    //*** GET Request
    public function filteredBanner()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.filteredbanner', compact('storesList'));
    }
    
    //*** GET Request
    public function filteredBannerCreate(Request $request)
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.filteredbannercreate', compact('storesList'));
    }

    //*** GET Request
    public function bottomcreate()
    {
        $storesList = Generalsetting::all();
        return view('admin.banner.bottomcreate', compact('storesList'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'required|mimes:jpeg,jpg,png,svg,gif',
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Banner();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/banners', $name);
            $input['photo'] = $name;
        }
        $data->fill($input)->save();
        //--- Logic Section Ends

        $banner = Banner::find($data->id);

        if ($request->has('stores')) {
            $banner->stores()->sync($input['stores']);
        }

        //--- Redirect Section
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Banner::findOrFail($id);
        $storesList = Generalsetting::all();
        $currentStores = $data->stores()->pluck('id')->toArray();
        return view('admin.banner.edit', compact('data', 'storesList', 'currentStores'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'mimes:jpeg,jpg,png,svg,gif,webp',
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Banner::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/banners', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/storage/images/banners/'.$data->photo) && !empty($data->photo)) {
                    unlink(public_path().'/storage/images/banners/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        //--- Logic Section Ends

        //associates with stores
        $data->stores()->detach();
        if ($request->has('stores')) {
            $data->stores()->sync($input['stores']);
        }

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Banner::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/banners/'.$data->photo) && !empty($data->photo)) {
            unlink(public_path().'/storage/images/banners/'.$data->photo);
        }

        //remove from any store
        $data->stores()->detach();

        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
