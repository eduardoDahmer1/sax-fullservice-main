<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivitylogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Activity::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
        ->editColumn('created_at', function (Activity $data) {
            setlocale(LC_ALL, \App\Helpers\Helper::strLocaleVariations($this->lang->locale));
            return $data->created_at->formatLocalized('%d/%m/%Y, %T');
        })
        ->editColumn('description', function (Activity $data) {
            if (strtolower($data->description) === 'deleted') {
                return '<span class="badge badge-danger">deleted</span>';
            }
            if (strtolower($data->description) === 'created') {
                return '<span class="badge badge-success">created</span>';
            }
            if (strtolower($data->description) === 'updated') {
                return '<span class="badge badge-info">updated</span>';
            }
            return $data->description;
        })
        ->editColumn('subject_id', function (Activity $data) {
            $line1 = 'nothing';
            if ($data->subject_id) {
                $id = $data->subject_id;
                $subject = $data->subject_type;
                $line1 = '<span class="badge badge-secondary">ID: '.$id.'</span> ' . $subject;
                $buttons = '<a href="' . route('admin-activitylog-properties', $data->id) . '" class="btn btn-sm btn-outline-secondary mt-1" target="_blank"> <i class="fas fa-external-link-alt"></i>' . 'Properties' . '</a>';
                $line1 .= '<br>' . $buttons;
            }
            return $line1;
        })
        ->editColumn('causer_id', function (Activity $data) {
            $line1 = '-';
            $line2 = '';
            if ($data->causer_id) {
                $id = $data->causer_id;
                $subject = $data->causer_type;
                $line1 = '<span class="badge badge-secondary">ID: '.$id.'</span> ' . $subject;
                $line2 = Admin::find($id);
                if (!$line2) {
                    $line2 = User::find($id);
                }
            }
            return $line1 . '<br>' . ($line2->name ?? 'system');
        })
        ->rawColumns(['description', 'subject_id', 'causer_id'])
        ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.activitylog.index');
    }

    public function properties($id)
    {
        return Activity::find($id)->properties;
    }
}
