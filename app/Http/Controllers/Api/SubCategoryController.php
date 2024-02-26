<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\SubCategoryController as AdminSubCategoryController;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Resources\SubCategoryResource;
use Symfony\Component\HttpFoundation\Response;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_categories = SubCategory::all();
        return SubCategoryResource::collection($sub_categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->api = true;
        $adminSubCategory = new AdminSubCategoryController();
        return $adminSubCategory->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sub_category = SubCategory::find($id);
        if(!empty($sub_category)){
            return new SubCategoryResource($sub_category);
        }else{
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->api = true;
        $sub_category = SubCategory::find($id);
        if(!empty($sub_category)){
            $adminSubCategory = new AdminSubCategoryController();
            $msg = $adminSubCategory->update($request, $id);
            if($msg){
                return response()->json(array('status' => 'ok'));
            }
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }else{
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub_category = SubCategory::find($id);
        if(!empty($sub_category)){
            $adminSubCategory = new AdminSubCategoryController();
            $msg = $adminSubCategory->destroy($id);
            if($msg){
                return response()->json(array('status' => 'ok'));
            }
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }else{
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }
    }
}
