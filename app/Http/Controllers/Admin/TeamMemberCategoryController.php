<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Models\TeamMemberCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TeamMemberCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = TeamMemberCategory::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereTranslationLike('name', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('action', function (TeamMemberCategory $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-cteam_member-edit', $data->id) . '" data-header="' . __('Edit Team Member Category') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-cteam_member-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.cteam_member.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.cteam_member.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => [
                "required",
                Rule::unique('team_member_category_translations', 'name')->where(function ($query) {
                    return $query->where('locale', "{$this->lang->locale}");
                })
            ],
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __("Name in :lang is required", ['lang' => $this->lang->language]),
            "{$this->lang->locale}.name.unique" => __("Name in :lang has already been taken", ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new TeamMemberCategory;
        $input = $this->removeEmptyTranslations($request->all());
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
        $data = TeamMemberCategory::findOrFail($id);
        return view('admin.cteam_member.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {

        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.name" => [
                "required",
                Rule::unique('team_member_category_translations', 'name')->ignore($id, 'team_member_category_id')->where(function ($query) {
                    return $query->where('locale', "{$this->lang->locale}");
                })
            ],
        ];
        $customs = [
            "{$this->lang->locale}.name.required" => __("Name in :lang is required", ['lang' => $this->lang->language]),
            "{$this->lang->locale}.name.unique" => __("Name in :lang has already been taken", ['lang' => $this->lang->language]),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = TeamMemberCategory::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function destroy($id)
    {

        try {
            $data = TeamMemberCategory::findOrFail($id);
            $data->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            $msg = [ 'msg' => __('Remove team member first !') ];
            return response()->json(array('errors' =>  $msg));
        }

        //--- Check If there any team member available, If Available Then Delete it
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }
}
