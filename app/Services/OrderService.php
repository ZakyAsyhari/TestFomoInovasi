<?php

namespace App\Services;

use App\Models\Flashsale;
use App\Models\Orders;
use App\Repositorys\OrderRepositori;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Class OrderService
{
    use ApiResponseTrait;
    protected $repository;
    public function __construct(OrderRepositori $repository)
    {
        $this->repository = $repository;
    }

   public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $orderDetails = [];
            $total = 0;

            $products = $this->getLockedProducts($data);

            foreach ($data['items'] as $item) {
                $product = $products[$item['product_id']];

                $finalPrice = $product->price;
                $productFlasSaleId = null;
                $this->validateStock($product, $item['qty']);
                if ($this->isFlashSaleActive($product->flashSale)) {
                    $remainingQuota = $this->getRemainingQuota($product->flashSale);
                    $qtyFlashSale = min($remainingQuota, $item['qty']);
                    $this->flashSaleSoldQty($product->flashSale, $qtyFlashSale);

                    /**==============================================================
                     * Ketika qty flas sale masih ada, ambil harga flash sale
                     * =============================================================
                     * */
                    if($qtyFlashSale > 0) {
                        $finalPrice = $product->flashSale->flashsale_price;
                        $productFlasSaleId = $product->flashSale->id;
                    }

                    /** ==============================================================
                     * bisa tambah kondisi jika flash sale sudah habis bisa return
                     * pesan flash sale sudah habis(optional)
                     * ===============================================================
                     */
                }


                $total += $finalPrice * $item['qty'];

                $orderDetails[] = [
                    'product_id' => $item['product_id'],
                    'flashsale_id' => $productFlasSaleId,
                    'qty' => $item['qty'],
                    'price' => $finalPrice,
                    'discount' => $product->discount ?? 0,
                    'total' => $finalPrice * $item['qty'],
                    'created_at' => now(),
                ];
                $this->reduceStock($product, $item['qty']);
            }

            /**==============================================================
             * Buat order
             *==============================================================
             */
            $order = $this->repository->orderCreate($data['customer_id'],$total,$orderDetails);
            return [
                'data' => $order,
                'message' => 'Order created successfully',
                'success' => true
            ];

        });
    }

    /**==============================================================
     * Race Condition
     * ==============================================================
     */
    private function getLockedProducts(array $data)
    {
        $productIds = collect($data['items'])->pluck('product_id')->toArray();
        return $this->repository->getProduct($productIds);
    }

    /**==============================================================
     * Validasi Stok
     * ==============================================================
     */
    private function validateStock($product, int $qty) : void
    {
        if ($product->stock < $qty) {
            throw new Exception(
                "Insufficient stock for product: {$product->name}"
            );
        }
    }

    /**==============================================================
     * Menghitung quota flash sale
     * ==============================================================
     */
    private function getRemainingQuota(FlashSale $flashSale): int
    {
        return max(0, $flashSale->qty - $flashSale->sold_qty);
    }

    /**==============================================================
     * Cek flash sale aktif
     * ==============================================================
     */
    private function isFlashSaleActive(?FlashSale $flashSale): bool
    {
        return $flashSale && $flashSale->isActive();
    }

    /**==============================================================
     * Menghitung sisa quota flash sale
     * ==============================================================
     */
    private function flashSaleSoldQty(Flashsale $flashsale, int $qtyFlashSale)
    {
        if ($qtyFlashSale > 0) {
            $flashsale->increment('sold_qty', $qtyFlashSale);
        }
    }

    /**==============================================================
     * Mengurangi stok
     * ==============================================================
     */
    private function reduceStock($product, int $qty): void
    {
        $product->decrement('stock', $qty);
    }

}
