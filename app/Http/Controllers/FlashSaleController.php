<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlashSaleRequest;
use App\Models\Flashsale;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Flashsale::all();
            return $this->successResponse($products);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FlashSaleRequest $request)
    {
        try {
            $product = Flashsale::create($request->all());
            return $this->successResponse($product);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FlashSaleRequest $request, string $id)
    {
        try {
            $product = Flashsale::find($id);
            if (!$product) {
                return $this->errorResponse('Product not found', Response::HTTP_NOT_FOUND);
            }
            $product->update($request->all());
            return $this->successResponse($product);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Flashsale::find($id);
            if (!$product) {
                return $this->errorResponse('Product not found', Response::HTTP_NOT_FOUND);
            }

            if ($product->orderDetails()->count() > 0) {
                return $this->errorResponse('Product has orders', Response::HTTP_BAD_REQUEST);
            }
            $product->delete();
            return $this->successResponse($product);
        } catch (Exception $th) {
            return $this->errorResponse($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
