<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = Page::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('header', function (Page $data) {
                $s = $data->header == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxHeader" id="checkbox-header-'.$data->id.'" name="'.route('admin-page-header', ['id1' => $data->id, 'id2' => $data->header]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('footer', function (Page $data) {
                $s = $data->footer == 1 ? 'checked' : '';
                return '<div class="fix-social-links-area social-links-area"><label class="switch"><input type="checkbox" class="droplinks drop-sucess checkboxFooter" id="checkbox-footer-'.$data->id.'" name="'.route('admin-page-footer', ['id1' => $data->id, 'id2' => $data->footer]).'"'.$s.'><span class="slider round"></span></label></div>';
            })
            ->addColumn('action', function (Page $data) {
                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a data-href="' . route('admin-page-edit', $data->id) . '" data-header="' . __('Edit Page') . '" class="edit" data-toggle="modal" data-target="#modal1"> 
                            <i class="fas fa-edit"></i> ' . __('Edit') . '
                        </a>
                        <a href="javascript:;" data-href="' . route('admin-page-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                            <i class="fas fa-trash-alt"></i> ' . __('Delete') . '
                        </a>
                    </div>
                </div>';
            })
            ->rawColumns(['header', 'footer', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.page.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.page.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $slug = $request->slug;
        $main = array('home', 'faq', 'contact', 'blog', 'cart', 'checkout');
        if (in_array($slug, $main)) {
            return response()->json(array('errors' => [0 => __('This slug has already been taken.')]));
        }
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required'
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Description in :lang is required', ['lang' => $this->lang->language])
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Page();
        $input = $this->withRequiredFields($request->all(), ['title', 'details']);

        // Translations section
        if (!empty($input[$this->lang->locale]['meta_tag'])) {
            $input[$this->lang->locale]['meta_tag'] = implode(',', $input[$this->lang->locale]['meta_tag']);
        }

        foreach ($this->locales as $loc) {
            if ($loc->locale === $this->lang->locale) {
                continue;
            }

            if (!empty($input[$loc->locale]['meta_tag'])) {
                $input[$loc->locale]['meta_tag'] = implode(',', $input[$loc->locale]['meta_tag']);
            }
        }
        // End of Translations section

        $data->fill($input)->save();
        //--- Logic Section Ends

          //-----Creating automatic slug
          $pages = Page::find($data->id);
          $pages->slug = Str::slug($data->title,'-').'-'.strtolower($data->id);
          $pages->update();
        //--- Redirect Section        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }

    //*** GET Request
    public function edit($id)
    {
        $data = Page::findOrFail($id);
        return view('admin.page.edit',compact('data'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $slug = $request->slug;
        $main = array('home','faq','contact','blog','cart','checkout');
        if (in_array($slug, $main)) {
        return response()->json(array('errors' => [ 0 => __('This slug has already been taken.') ]));          
        }
        $rules = [
            "{$this->lang->locale}.title" => 'required',
            "{$this->lang->locale}.details" => 'required',
            'slug' => 'unique:pages,slug,'.$id
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Title in :lang is required', ['lang' => $this->lang->language]),
            "{$this->lang->locale}.details.required" => __('Description in :lang is required', ['lang' => $this->lang->language]),
            'slug.unique' => __('This slug has already been taken.')
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }        
        //--- Validation Section Ends

        //--- Logic Section
        $data = Page::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['title', 'details']);

        //Translation section
        $input[$this->lang->locale]['meta_tag'] = (
            isset($input[$this->lang->locale]['meta_tag']) ?
            implode(',', $input[$this->lang->locale]['meta_tag']) :
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

        }

        //End Translation section

        $data->update($input);
        //--- Logic Section Ends
         //----Slug automatic
         $page = Page::findOrFail($id);
         $page->slug = Str::slug($page->title,'-').'-'.strtolower($page->id);
         $page->update($input);

        //--- Redirect Section     
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends           
    }
      //*** GET Request Header
      public function header($id1,$id2)
        {
            $data = Page::findOrFail($id1);
            $data->header = $id2;
            $data->update();
        }
      //*** GET Request Footer
      public function footer($id1,$id2)
        {
            $data = Page::findOrFail($id1);
            $data->footer = $id2;
            $data->update();
        }


    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Page::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }
}