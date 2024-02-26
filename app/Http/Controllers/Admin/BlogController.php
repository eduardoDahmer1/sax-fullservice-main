<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Blog::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->editColumn('photo', function (Blog $data) {
                if (file_exists(public_path().'/storage/images/blogs/'.$data->photo)) {
                    return asset('storage/images/blogs/'.$data->photo);
                } else {
                    return asset('assets/images/noimage.png');
                }
            })
            ->addColumn('action', function (Blog $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-blog-edit', $data->id) . '" data-header="' . __('Edit Blog') . '" class="edit" data-toggle="modal" data-target="#modal1">
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-blog-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
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
        return view('admin.blog.index');
    }

    //*** GET Request
    public function create()
    {
        $cats = BlogCategory::all();
        return view('admin.blog.create', compact('cats'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'photo'      => 'required|mimes:jpeg,jpg,png,svg,webp',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Description in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Blog();
        $input = $this->withRequiredFields($request->all(), ['title', 'details']);
        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/blogs', $name);
            $input['photo'] = $name;
        }

        // Translations section
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        if (!empty($input[$this->lang->locale]['tags'])) {
            $input[$this->lang->locale]['tags'] = implode(',', $input[$this->lang->locale]['tags']);
        }

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            if (!empty($input[$loc->locale]['meta_tag'])) {
                $input[$loc->locale]['meta_tag'] = implode(',', $input[$loc->locale]['meta_tag']);
            }

            if (!empty($input[$loc->locale]['tags'])) {
                $input[$loc->locale]['tags'] = implode(',', $input[$loc->locale]['tags']);
            }
        }
        // End of Translations section

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
        $cats = BlogCategory::all();
        $data = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('data', 'cats'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'photo'      => 'mimes:jpeg,jpg,png,svg,webp',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Description in :lang is required', ['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = Blog::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['title', 'details']);

        if ($file = $request->file('photo')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('storage/images/blogs', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/storage/images/blogs/' . $data->photo) && !empty($data->photo)) {
                    unlink(public_path() . '/storage/images/blogs/' . $data->photo);
                }
            }
            $input['photo'] = $name;
        }

        //Translation section
        $input[$this->lang->locale]['meta_tag'] = (
            isset($input[$this->lang->locale]['meta_tag']) ?
            implode(',', $input[$this->lang->locale]['meta_tag']) :
            null
        );

        $input[$this->lang->locale]['tags'] = (
            isset($input[$this->lang->locale]['tags']) ?
            implode(',', $input[$this->lang->locale]['tags']) :
            null
        );

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            $input[$loc->locale]['meta_tag'] = (
                isset($input[$loc->locale]['meta_tag']) ?
                implode(',', $input[$loc->locale]['meta_tag']) :
                null
            );

            $input[$loc->locale]['tags'] = (
                isset($input[$loc->locale]['tags']) ?
                implode(',', $input[$loc->locale]['tags']) :
                null
            );
        }

        //End Translation section

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
        $data = Blog::findOrFail($id);
        //If Photo Doesn't Exist
        if ($data->photo == null) {
            $data->delete();
            //--- Redirect Section
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
            //--- Redirect Section Ends
        }
        //If Photo Exist
        if (file_exists(public_path().'/storage/images/blogs/'.$data->photo) && !empty($data->photo)) {
            unlink(public_path().'/storage/images/blogs/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }
}
