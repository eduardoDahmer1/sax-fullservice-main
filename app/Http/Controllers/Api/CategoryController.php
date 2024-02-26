<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
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
        $adminCategory = new AdminCategoryController();
        return $adminCategory->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if(!empty($category)){
            return new CategoryResource($category);
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
        $category = Category::find($id);
        if(!empty($category)){
            $adminCategory = new AdminCategoryController();
            $msg = $adminCategory->update($request, $id);
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
        $category = Category::find($id);
        if(!empty($category)){
            $adminCategory = new AdminCategoryController();
            $msg = $adminCategory->destroy($id);
            if($msg){
                return response()->json(array('status' => 'ok'));
            }
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }else{
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }
    }
}
