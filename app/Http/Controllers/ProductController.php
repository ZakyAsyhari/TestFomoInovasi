<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        return $this->successResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = Products::create($request->all());
            return $this->successResponse($product);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Products::find($id);
        if (!$product) {
            return $this->errorResponse('Product not found', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        try {
            $product = Products::find($id);
            if (!$product) {
                return $this->errorResponse('Product not found', Response::HTTP_NOT_FOUND);
            }
            $product->update($request->all());
            return $this->successResponse($product);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Products::find($id);
            if (!$product) {
                return $this->errorResponse('Product not found', Response::HTTP_NOT_FOUND);
            }
            $product->delete();
            return $this->successResponse($product);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
