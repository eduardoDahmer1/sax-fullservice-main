<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use App\Models\AdminLanguage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Validator;

class AdminLanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = AdminLanguage::orderBy('id');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->addColumn('language', function (AdminLanguage $data) {
                if ($data->is_default) {
                    $badge = ' <span class="badge badge-pill badge-primary">'.__("Default").'</span>';
                    return __($data->language).$badge;
                } else {
                    return __($data->language);
                }
            })
            ->addColumn('action', function (AdminLanguage $data) {
                $default = $data->is_default == 1 ? '' : '<a class="status" data-href="' . route('admin-tlang-st', ['id1' => $data->id, 'id2' => 1]) . '"><i class="icofont-globe"></i> ' . __('Set Default') . '</a>';
                if (empty($default)) {
                    return '';
                }

                return '
                <div class="godropdown">
                    <button class="go-dropdown-toggle"> ' . __('Actions') . '<i class="fas fa-chevron-down"></i></button>
                    <div class="action-list">
                        <a href="' . route('admin-tlang-edit', $data->id) . '">' . $default . '
                    </div>
                </div>';
            })
            ->rawColumns(['action','language'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    //*** GET Request
    public function index()
    {
        return view('admin.adminlanguage.index');
    }

    //*** GET Request
    public function create()
    {
        return view('admin.adminlanguage.create');
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            'locale'      => 'required|unique:admin_languages,name',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section

        $input = $request->all();
        $data = new AdminLanguage();

        $data->language = $input['language'];
        $data->name = $input['locale'];
        $data->rtl = $input['rtl'];
        $data->file = 'admin_' . $data->name . '.json';

        unset($input['_token']);
        unset($input['language']);
        unset($input['rtl']);

        if (file_exists(resource_path("lang") . '/base_admin_' . $data->name . '.json')) {
            copy(resource_path("lang") . '/base_admin_' . $data->name . '.json', resource_path("lang") . '/admin_' . $data->name . '.json');
        } else {
            $fields = $this->getTranslationKeys();
            sort($fields, SORT_STRING | SORT_FLAG_CASE);
            foreach ($fields as $field) {
                $translations[$field] = "";
            }
            $mydata = json_encode($translations);
            file_put_contents(resource_path("lang") . '/' . $data->file, $mydata);
        }
        $data->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request
    public function edit($id)
    {
        $fields = $this->getTranslationKeys();

        $keys = array_flip($fields);

        $data = AdminLanguage::findOrFail($id);
        if (file_exists(resource_path("lang") . '/' . $data->file)) {
            $data_results = file_get_contents(resource_path("lang") . '/' . $data->file);
            $langJson = json_decode($data_results, true);
            $langJson = array_filter($langJson);
            if (file_exists(resource_path("lang") . '/base_admin_' . $data->name . '.json')) {
                $data_results_base = file_get_contents(resource_path("lang") . '/base_admin_' . $data->name . '.json');
                $langJsonBase = json_decode($data_results_base, true);
                $newBaseKeys = array_diff_key($langJsonBase, $langJson);
                $langJson = array_merge($newBaseKeys, $langJson);
            }
            $newKeys = array_diff_key($keys, $langJson);
            $langEdit = array_merge($newKeys, $langJson);
        } elseif (file_exists(resource_path("lang") . '/base_admin_' . $data->name . '.json')) {
            $data_results = file_get_contents(resource_path("lang") . '/base_admin_' . $data->name . '.json');
            $langJson = json_decode($data_results, true);

            $newKeys = array_diff_key($keys, $langJson);
            $langEdit = array_merge($newKeys, $langJson);
        } else {
            $langEdit = $keys;
        }
        ksort($langEdit, SORT_STRING | SORT_FLAG_CASE);
        return view('admin.adminlanguage.edit', compact('data', 'langEdit'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'locale' => [
                'required',
                Rule::unique('admin_languages', 'name')->ignore($id)
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $new = null;
        $input = $request->all();
        $data = AdminLanguage::findOrFail($id);
        $oldFile = $data->file; //the locale can be edited
        $oldLocale = $data->name;
        $data->language = $input['language'];
        $data->name = $input['locale'];
        $data->rtl = $input['rtl'];
        $data->file = 'admin_' . $data->name . '.json';

        unset($input['_token']);
        unset($input['language']);
        unset($input['rtl']);

        if ($input['locale'] != $oldLocale) {
            if ($id == 1) {
                return __("You don't have access to change this locale");
            }
            if (file_exists(resource_path("lang") . '/' . $oldFile) && !empty($oldFile)) {
                unlink(resource_path("lang") . '/' . $oldFile);
            }
            if (file_exists(resource_path("lang") . '/base_admin_' . $data->name . '.json')) {
                copy(resource_path("lang") . '/base_admin_' . $data->name . '.json', resource_path("lang") . '/admin_' . $data->name . '.json');
            } else {
                $fields = $this->getTranslationKeys();
                sort($fields, SORT_STRING | SORT_FLAG_CASE);
                foreach ($fields as $field) {
                    $translations[$field] = "";
                }
                $mydata = json_encode($translations);
                file_put_contents(resource_path("lang") . '/' . $data->file, $mydata);
            }
        } else {
            if (file_exists(resource_path("lang") . '/' . $oldFile)) {
                $data_results = file_get_contents(resource_path("lang") . '/' . $oldFile);
                $langJson = json_decode($data_results, true);
            } elseif (file_exists(resource_path("lang") . '/base_admin_' . $data->name . '.json')) {
                $data_results = file_get_contents(resource_path("lang") . '/base_admin_' . $data->name . '.json');
                $langJson = json_decode($data_results, true);
            } else {
                $fields = $this->getTranslationKeys();
                $langJson = array_flip($fields);
            }

            $keys = $request->keys;
            $values = $request->values;
            foreach (array_combine($keys, $values) as $key => $value) {
                $n = str_replace("_", " ", $key);
                $new[$n] = (!empty($value) ? $value : "");
            }

            $lang = array_merge($langJson, $new);
            ksort($lang, SORT_STRING | SORT_FLAG_CASE);

            $mydata = json_encode($lang);
            file_put_contents(resource_path("lang") . '/' . $data->file, $mydata);
        }
        $data->update();

        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function status($id1, $id2)
    {
        $data = AdminLanguage::findOrFail($id1);
        $data->is_default = $id2;
        $data->update();
        $data = AdminLanguage::where('id', '!=', $id1)->update(['is_default' => 0]);
        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        if ($id == 1) {
            return __("You don't have access to remove this language.");
        }
        $data = AdminLanguage::findOrFail($id);
        if ($data->is_default == 1) {
            return __("You can not remove default language.");
        }
        $oldFile = $data->file;
        if (file_exists(resource_path("lang") . '/' .$oldFile) && !empty($oldFile)) {
            unlink(resource_path("lang") . '/' . $oldFile);
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends
    }

    /**
     * Get translation strings from admin views
     *
     * @return array
     */
    private function getTranslationKeys()
    {
        // Traversal logic based and adapted from https://github.com/joedixon/laravel-translation

        $translationMethods = ['trans', '__'];
        $scanPaths = [implode(",", config("view.paths")), app_path("Http/Controllers"), app_path("Providers"), resource_path("lang"), app_path("Traits")];
        $disk = new Filesystem;

        $temp = [];
        $results = [];

        $matchingPattern =
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(' . implode('|', $translationMethods) . ')' . // Must start with one of the functions
            "\(" . // Match opening parentheses
            "[\'\"]" . // Match " or '
            '(' . // Start a new group to match:
            '.+' . // Must start with group
            ')' . // Close group
            "[\'\"]" . // Closing quote
            "[\),]";  // Close parentheses or new parameter

        $files = $disk->allFiles($scanPaths);

        foreach ($files as $file) {
            if (strstr(strtolower($file), "admin") || strstr(strtolower($file), "lang") || strstr(strtolower($file), "provider")) {
                if (preg_match_all("/$matchingPattern/siU", $file->getContents(), $matches)) {
                    foreach ($matches[2] as $key) {
                        $temp[] = $key;
                    }
                }
            }
        }

        $results = array_unique($temp);
        return $results;
    }
}
