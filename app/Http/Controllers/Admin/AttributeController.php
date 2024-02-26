<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Attribute;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Childcategory;
use App\Models\AttributeOption;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AttributeController extends Controller
{
    public function __construct()
    {
      parent::__construct();
    }

    public function datatables(Request $request){
      $type = $request->route('type');
      switch($type){
        case "category":
          $attributable_type = "App\Models\Category";
        break;
        case "subcategory":
          $attributable_type = "App\Models\Subcategory";
        break;
        case "childcategory":
          $attributable_type = "App\Models\Childcategory";
        break;
      }
      $datas = Attribute::where('attributable_type', $attributable_type)->where('attributable_id', $request->route('id'))->orderBy('id', 'asc');
      return Datatables::of($datas)
      ->addColumn('action', function (Attribute $data) {
          return '
          <div class="godropdown">
              <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
              <div class="action-list">
                  <a data-href="'.route('admin-attr-edit', $data->id).'" data-header="' . __('Edit Attribute') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __('Edit') . '</a>
                  <a href="javascript:;" data-href="' . route('admin-attr-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> ' . __('Delete') . '</a>
              </div>
          </div>';
      })
      ->addColumn('name', function(Attribute $data){
        return $data->name;
      })
      ->addColumn('opt_count', function(Attribute $data){
        return $data->attribute_options->count();
      })
      ->rawColumns(['action', 'name', 'opt_count'])
      ->toJson();
    }

    public function attrCreateForCategory($catid) {
      $data = Category::findOrFail($catid);
      $type = 'category';
      return view('admin.attribute.create', compact('data', 'type'));
    }

    public function attrCreateForSubcategory($subcatid) {
      $data = Subcategory::findOrFail($subcatid);
      $type = 'subcategory';
      return view('admin.attribute.create', compact('data', 'type'));
    }

    public function attrCreateForChildcategory($childcatid) {
      $data = Childcategory::findOrFail($childcatid);
      $type = 'childcategory';
      return view('admin.attribute.create', compact('data', 'type'));
    }

    public function store(Request $request) {
      //--- Validation Section
      $rules = [
          "{$this->lang->locale}.name" => [
              'required',
              function ($attribute, $value, $fail) {
                  if (strtolower($value) == 'color' || strtolower($value) == 'size') {
                      $fail(__('Attribute name cannot be color and size.'));
                  }
              },
          ],
        "{$this->lang->locale}.options" => 'required',
        // "{$this->storeLocale->locale}.options" => 'required'
      ];
      $customs = [
        "{$this->lang->locale}.name.required" => __('Attribute Name in :lang is required',['lang' => $this->lang->language]),
        "{$this->lang->locale}.options.required" => __('Option Name in :lang is required',['lang' => $this->lang->language]),
               ];
      $validator = Validator::make($request->all(), $rules, $customs);

      if ($validator->fails()) {
        return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
      }
      //--- Validation Section Ends

      //--- Logic Section
      $in = $this->removeEmptyTranslations($request->all());

      $in['attributable_id'] = $request->category_id;
      if ($request->type == 'category') {
        $in['attributable_type'] = 'App\Models\Category';
      } elseif ($request->type == 'subcategory') {
        $in['attributable_type'] = 'App\Models\Subcategory';
      } elseif ($request->type == 'childcategory') {
        $in['attributable_type'] = 'App\Models\Childcategory';
      }
      $in['input_name'] = Str::slug($in[$this->lang->locale]['name'], '_');

      if (request()->has('price_status')) {
        $in['price_status'] = 1;
      } else {
        $in['price_status'] = 0;
      }

      if (request()->has('show_price')) {
        $in['show_price'] = 1;
      } else {
        $in['show_price'] = 0;
      }

      if (request()->has('details_status')) {
        $in['details_status'] = 1;
      } else {
        $in['details_status'] = 0;
      }

      $newAttr = Attribute::create($in);
      $newAttr->input_name .= $newAttr->id;
      $newAttr->save();
      //--- Logic Section Ends

    /**
     * Prepare data to be stored in the attribute option table.
     * It will first add the data for language 1 and check if there are
     * translations in the input for the option multi dimensional array, using
     * array_filter to remove nullable values and checking with array_keys_exists
     */
    foreach ($in[$this->lang->locale]['options'] as $langKey => $langOption) {
      $langOption = preg_replace("/[^a-zA-Z0-9\s]/", "", $langOption);
      $desc = array_key_exists('description', $in[$this->lang->locale]) ? $in[$this->lang->locale]['description'][$langKey] : "";
      $data = [
        'attribute_id' => $newAttr->id,
        "{$this->lang->locale}" => ['name' => $langOption, 'description' => $desc],
      ];

      foreach($this->locales as $locale) {
        if($locale->locale === $this->lang->locale) {
          continue;
        }
        $locDesc = array_key_exists('description', $in[$locale->locale]) ? $in[$locale->locale]['description'][$langKey] : "";
        if(array_key_exists($langKey, array_filter($in[$locale->locale]['options']))) {
          $data[$locale->locale] = ['name' => $in[$locale->locale]['options'][$langKey], 'description' => $locDesc];
        }
      }
      $newOpt = AttributeOption::create($data);
    }

      //--- Redirect Section
      $msg = __('New Data Added Successfully.');
      return response()->json($msg);
      //--- Redirect Section Ends
    }

    public function manage(Request $request, $id) {
      if ($request->type == 'category') {
        $data['data'] = Category::find($id);
      }
      if ($request->type == 'subcategory') {
        $data['data'] = Subcategory::find($id);
      }
      if ($request->type == 'childcategory') {
        $data['data'] = Childcategory::find($id);
      }
      $data['type'] = $request->type;
      return view('admin.attribute.manage', $data);
    }

    public function edit($id) {
      $data['attr'] = Attribute::find($id);
      return view('admin.attribute.edit', $data);
    }

  public function update(Request $request, $id)
  {
    //--- Validation Section
    $rules = [
      "{$this->lang->locale}.name" => [
        'required',
        function ($attribute, $value, $fail) {
          if (strtolower($value) == 'color' || strtolower($value) == 'size') {
            $fail(__('Attribute name cannot be color and size.'));
          }
        },
      ],
      "{$this->lang->locale}.options" => 'required',
      // "{$this->storeLocale->locale}.options" => 'required'
    ];
    $customs = [
      "{$this->lang->locale}.name.required" => __('Attribute Name in :lang is required', ['lang' => $this->lang->language]),
      "{$this->lang->locale}.options.required" => __('Option Name in :lang is required', ['lang' => $this->lang->language]),
    ];
    $validator = Validator::make($request->all(), $rules, $customs);

    if ($validator->fails()) {
      return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    }
    //--- Validation Section Ends

    $attr = Attribute::find($id);

    $input = $this->removeEmptyTranslations($request->all(), $attr);

    if (request()->has('price_status')) {
      $input['price_status'] = 1;
    } else {
      $input['price_status'] = 0;
    }

    if (request()->has('show_price')) {
      $input['show_price'] = 1;
    } else {
      $input['show_price'] = 0;
    }

    if (request()->has('details_status')) {
      $input['details_status'] = 1;
    } else {
      $input['details_status'] = 0;
    }

    $attr->update($input);

    $attrOpts = AttributeOption::where('attribute_id', $id)->get();

    /**
     * Prepare data to be stored in the attribute option table.
     * It will first add the data for language 1 and check if there are
     * translations in the input for the option multi dimensional array, using
     * array_filter to remove nullable values and checking with array_keys_exists.
     * It will also parse de IDs sent to update the attribute option instead of
     * recreating it.
     */
    foreach ($input[$this->lang->locale]['options']['name'] as $langKey => $langOption) {
      
      $langOption = preg_replace("/[^a-zA-Z0-9\s]/", "", $langOption);

      $optionId = (array_key_exists($langKey, $input[$this->lang->locale]['options']['id']) ? $input[$this->lang->locale]['options']['id'][$langKey] : null);

      $desc = array_key_exists('description', $input[$this->lang->locale]) ? $input[$this->lang->locale]['description'][$langKey] : "";

      $data = [
        'attribute_id' => $id,
        "{$this->lang->locale}" => ['name' => $langOption, 'description' => $desc],
      ];

      foreach ($this->locales as $locale) {
        if ($locale->locale === $this->lang->locale) {
          continue;
        }

        $locDesc = array_key_exists('description', $input[$locale->locale]) ? $input[$locale->locale]['description'][$langKey] : "";
        if (array_key_exists($langKey, array_filter($input[$locale->locale]['options']['name']))) {
          $data[$locale->locale] = ['name' => $input[$locale->locale]['options']['name'][$langKey], 'description' => $locDesc];
        }
      }

      //update the attribute if ID is found
      if ($optionId) {
        $attrUpdate = $attrOpts->find($optionId);
        if ($attrUpdate) {
          $attrUpdate->update($data);
        }
        continue;
      }

      // otherwise create a new option
      $newOpt = AttributeOption::create($data);
    }

    //--- Redirect Section
    $msg = __('Data Updated Successfully.');
    return response()->json($msg);
    //--- Redirect Section Ends
  }

    public function options($id) {
      $options = AttributeOption::where('attribute_id', $id)->get();
      return response()->json($options);
    }

    public function destroy($id) {
      $attr = Attribute::find($id);
      $attr->attribute_options()->delete();
      $attr->delete();

      $msg = __('Data Deleted Successfully.');
      return response()->json($msg);
      //--- Redirect Section Ends
    }

    public function deleteAttrOpt($optionid) {
        
      $attr = AttributeOption::find($optionid)->delete();;
     
    
      //--- Redirect Section
      Session::flash('success', __('Data deleted successfully!'));
      $msg="oi";
      return response()->json($msg);
      //--- Redirect Section Ends 
  }




}
