<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Resources\BrandResource;
use Symfony\Component\HttpFoundation\Response;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        return BrandResource::collection($brands);
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
        $adminBrand = new AdminBrandController();
        return $adminBrand->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::find($id);
        if(!empty($brand)){
            return new BrandResource($brand);
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
        $brand = Brand::find($id);
        if(!empty($brand)){
            $adminBrand = new AdminBrandController();
            $msg = $adminBrand->update($request, $id);
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
        $brand = Brand::find($id);
        if(!empty($brand)){
            $adminBrand = new AdminBrandController();
            $msg = $adminBrand->destroy($id);
            if($msg){
                return response()->json(array('status' => 'ok'));
            }
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }else{
            return response()->json(array('errors' => ['Not found']),Response::HTTP_BAD_REQUEST);
        }
    }
}
