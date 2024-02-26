<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\ChildCategoryController as AdminChildCategoryController;
use App\Http\Controllers\Controller;
use App\Models\ChildCategory;
use Illuminate\Http\Request;
use App\Http\Resources\ChildCategoryResource;
use Symfony\Component\HttpFoundation\Response;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $child_categories = ChildCategory::all();
        return ChildCategoryResource::collection($child_categories);
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
        $adminChildCategory = new AdminChildCategoryController();
        return $adminChildCategory->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $child_category = ChildCategory::find($id);
        if(!empty($child_category)){
            return new ChildCategoryResource($child_category);
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
        $child_category = ChildCategory::find($id);
        if(!empty($child_category)){
            $adminChildCategory = new AdminChildCategoryController();
            $msg = $adminChildCategory->update($request, $id);
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
        $child_category = ChildCategory::find($id);
        if(!empty($child_category)){
            $adminChildCategory = new AdminChildCategoryController();
            $msg = $adminChildCategory->destroy($id);
            if($msg){
                return response()->json(array('status' => 'ok'));
            }
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }else{
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }
    }
}
