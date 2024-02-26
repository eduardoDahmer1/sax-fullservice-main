<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Admin::where('role_id', '!=', 0)->where('id', '!=', Auth::guard('admin')->user()->id)->orderBy('id');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->addColumn('role', function (Admin $data) {
            $role = $data->role_id == 0 ? __('No Role') : $data->role->name;
            return $role;
        })
        ->editColumn('created_at', function (Admin $data) {
            $created_at = $data->created_at == null ? __('No Date') : \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->formatLocalized('%d/%m/%Y, %T');
            return $created_at;
        })
        ->addColumn('action', function (Admin $data) {
            return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-staff-edit', $data->id) . '" data-header="' . __('Edit Staff') . '" class="edit" data-toggle="modal" data-target="#modal1">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-staff-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
        })
        ->rawColumns(['action'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.staff.index');
    }

    //*** GET Request
    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.create', compact('roles'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'photo'      => 'mimes:jpeg,jpg,png,svg',
               'email'   => 'required|email|unique:admins',
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        if ($request->role_id == 0) {
            return response()->json('Impossible to create user with such role.');
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Admin();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/admins', $name);
            $input['photo'] = $name;
        }
        $input['role'] = 'Staff';
        $input['password'] = bcrypt($request['password']);
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }


    public function edit($id)
    {
        $data = Admin::findOrFail($id);
        $roles = Role::all();
        return view('admin.staff.edit', compact('data', 'roles'));
    }

    public function update(Request $request, $id)
    {
        //--- Validation Section
        if ($id != Auth::guard('admin')->user()->id) {
            $rules =
            [
                'photo' => 'mimes:jpeg,jpg,png,svg',
                'email' => 'unique:admins,email,'.$id
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            if ($request->role_id == 0) {
                return response()->json('Impossible to create user with such role.');
            }
            //--- Validation Section Ends
            $input = $request->all();
            $data = Admin::findOrFail($id);
            if ($file = $request->file('photo')) {
                $name = time().$file->getClientOriginalName();
                $file->move('storage/images/admins/', $name);
                if ($data->photo != null) {
                    if (file_exists(public_path().'/storage/images/admins/'.$data->photo)) {
                        unlink(public_path().'/storage/images/admins/'.$data->photo);
                    }
                }
                $input['photo'] = $name;
            }
            if ($request->password == '') {
                $input['password'] = $data->password;
            } else {
                $input['password'] = bcrypt($request->password);
            }
            $data->update($input);
            $msg = __('Data Updated Successfully.');
            return response()->json($msg);
        } else {
            $msg = 'You can not change your role.';
            return response()->json($msg);
        }
    }

    //*** GET Request
    public function show($id)
    {
        $data = Admin::findOrFail($id);
        return view('admin.staff.show', compact('data'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Admin::findOrFail($id);
        if ($data->role_id == 0) {
            return "You don't have access to remove this admin";
        }
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/admins/'.$data->photo)) {
            unlink(public_path().'/storage/images/admins/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
