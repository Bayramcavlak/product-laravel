<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\ProductHistory;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Jobs\SendProductNotificationJob;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        $filter = [
            'name' => request('name'),
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
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully',
            'data' => ProductResource::make($product),
        ]);
    }

    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product creation failed',
                'data' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        SendProductNotificationJob::dispatch($product, 'store')->onQueue('email');

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product,
        ], Response::HTTP_CREATED);
    }

    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::where('id', $id)
                ->lockForUpdate()
                ->with('user')
                ->first();
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                ], Response::HTTP_NOT_FOUND);
            }
            $product->update($request->all());
            ProductHistory::create([
                'product_id' => $product->id,
                'action' => 'update',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Product update failed',
                'data' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        SendProductNotificationJob::dispatch($product, 'update')->onQueue('email');

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
            'data' => $product,
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $product = Product::where('id', $id)->first();
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                ], Response::HTTP_NOT_FOUND);
            }
            $product->delete();
            ProductHistory::create([
                'product_id' => $product->id,
                'action' => 'delete',
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Product delete failed',
                'data' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        SendProductNotificationJob::dispatch($product, 'delete')->onQueue('email');

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ], Response::HTTP_OK);
    }
}
