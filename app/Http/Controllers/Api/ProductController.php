<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductHistory;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $filter = [
            'name' => request('name'),
            'status' => request('status'),
            'type' => request('type'),
            'user_id' => request('user_id'),
        ];
        $products = Product::filter($filter)->with('user')->paginate(15);
        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => ProductCollection::make($products),
        ]);
    }

    public function show($id)
    {
        $product = Product::where('id', $id)->with('user')->first();
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully',
            'data' => ProductResource::make($product),
        ]);
    }

    // store
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::create($request->all());
            ProductHistory::create([
                'product_id' => $product->id,
                'action' => 'store',
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Product creation failed',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
