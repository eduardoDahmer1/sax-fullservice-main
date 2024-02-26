<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ProductResource::collection(Product::when($request->query('q') ?? false, function ($query, $search) {
            $query->whereHas('translations', function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            });
        })->paginate());
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
        $request->type = "Physical";
        $adminProduct = new AdminProductController();
        return $adminProduct->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!empty($product)) {
            return new ProductResource($product);
        } else {
            return response()->json(array('errors' => ['Not found']), Response::HTTP_BAD_REQUEST);
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
        $request->type = "Physical";
        $adminProduct = new AdminProductController();
        return $adminProduct->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!empty($product)) {
            $adminProduct = new AdminProductController();
            $msg = $adminProduct->destroy($id);
            if ($msg) {
                return response()->json(array('status' => 'ok'));
            }
            return response()->json(array('errors' => ['Not found']), Response::HTTP_BAD_REQUEST);
        } else {
            return response()->json(array('errors' => ['Not found']), Response::HTTP_BAD_REQUEST);
        }
    }
}
