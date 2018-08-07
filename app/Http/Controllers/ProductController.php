<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    const PAGINATE = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('List ' . self::PAGINATE . ' products');

        $product = Product::paginate(self::PAGINATE);

        return ProductResource::collection($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:50',
            'description' => 'required|max:200',
            'price'       => 'required|regex:/^\d*(\.\d{1,2})?$/'
        ]);

        if ( ! $validator->passes()) {
            Log::info('Add product validation failed: ' . json_encode($validator->errors()->all()));

            return response()->json(['message' => $validator->errors()->all()],
                Response::HTTP_BAD_REQUEST)->withCallback($request->input('callback'));
        }

        $product = Product::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price
        ]);

        Log::info('Product saved successfully with productID = ' . $product->id);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $product = Product::find($id);

        if ( ! $product) {
            Log::info("Product not found with id = $id");

            return response()->json(['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND)->withCallback($request->input('callback'));
        }

        Log::info('Show product with Id = ' . $id);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required_without_all:description,price|max:50',
            'description' => 'required_without_all:name,price|max:200',
            'price'       => 'required_without_all:name,description|regex:/^\d*(\.\d{1,2})?$/'
        ]);

        if ( ! $validator->passes()) {
            Log::info('Update product validation failed: ' . json_encode($validator->errors()->all()));

            return response()->json(['message' => $validator->errors()->all()],
                Response::HTTP_BAD_REQUEST)->withCallback($request->input('callback'));
        }

        $product = Product::find($id);

        if ( ! $product) {
            Log::info("Product not found with id = $id");

            return response()->json(['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND)->withCallback($request->input('callback'));
        }

        $product->update($request->only(['name', 'description', 'price']));
        Log::info("Product updated successfully with productID = $id");

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);

        if ( ! $product) {
            Log::info("Product not found with id = $id");

            return response()->json(['message' => 'Product not found'],
                Response::HTTP_NOT_FOUND)->withCallback($request->input('callback'));
        }

        $product->delete();
        Log::info("Product deleted successfully with productID = $id");

        return response()->json(['message' => "Product deleted successfully"],
            Response::HTTP_OK)->withCallback($request->input('callback'));
    }
}
