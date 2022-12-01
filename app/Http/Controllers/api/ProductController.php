<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\APIPaginateCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->per_page ? $request->per_page : 10;
        $currentPage = $request->current_page ? $request->current_page : 1;

        $products = Product::join('categories','categories.id','=','products.category_id')->select('products.*','categories.name as category_name')->where('created_by',auth()->user()->id)->paginate($perPage, ["*"], "page", $currentPage);
        $response = new APIPaginateCollection($products, ProductResource::class);
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Product::create($request->only("name", "price","category_id","created_by","quantity"));
        return response()->json(["data" => [
            "success" => true
        ]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::findOrFail($id);
        $response = new ProductResource($product);
        return response()->json(["data" => $response]);
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
        //
        $product = Product::findOrFail($id);
        $product->update($request->only("name", "price","quantity","category_id"));
        return response()->json(["data" => [
            "success" => true
        ]]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Product::findOrFail($id)->delete();
        return response()->json(["data" => [
            "success" => true
        ]]);
    }

    public function getCategory(Request $request){
        //
        $perPage = $request->per_page ? $request->per_page : 10;
        $currentPage = $request->current_page ? $request->current_page : 1;

        $categories = Category::paginate($perPage, ["*"], "page", $currentPage);
        $response = new APIPaginateCollection($categories, CategoryResource::class);
        return response()->json($response);
    }
}
