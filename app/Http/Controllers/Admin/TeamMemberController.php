<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\TeamMemberCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = TeamMember::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('photo', function (TeamMember $data) {
                if (file_exists(public_path().'/storage/images/team_member/'.$data->photo)) {
                    return asset('storage/images/team_member/'.$data->photo);
                } else {
                    return asset('assets/images/noimage.png');
                }
            })
            ->editColumn('category_id', function (TeamMember $data) {
                $category_id = TeamMemberCategory::find($data->category_id);
                return $category_id->name;
            })
            ->addColumn('action', function (TeamMember $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-team_member-edit', $data->id) . '" data-header="' . __('Edit Team Member') . '" class="edit" data-toggle="modal" data-target="#modal1">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-team_member-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['photo', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.team_member.index');
    }
    //*** GET Request
    public function create()
    {
        $cats = TeamMemberCategory::all();
        return view('admin.team_member.create', compact('cats'));
    }
    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'photo'     => 'mimes:jpeg,jpg,png,svg',
            'category_id' => 'exists:team_member_categories,id',
            'name'      => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        //--- Logic Section
        $data = new TeamMember();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/team_member', $name);
            $input['photo'] = $name;
        }
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
        $cats = TeamMemberCategory::all();
        $data = TeamMember::findOrFail($id);
        return view('admin.team_member.edit', compact('data', 'cats'));
    }
    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'photo'     => 'mimes:jpeg,jpg,png,svg',
            'category_id' => 'exists:team_member_categories,id',
            'name'      => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        //--- Logic Section
        $data = TeamMember::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/team_member', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . 'storage/images/team_member' . $data->photo) && !empty($data->photo)) {
                    unlink(public_path() . 'storage/images/team_member' . $data->photo);
                }
            }
            $input['photo'] = $name;
        }
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
        $data = TeamMember::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path() . '/storage/images/blogs/' . $data->photo) && !empty($data->photo)) {
            unlink(public_path() . '/storage/images/blogs/' . $data->photo);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
