<?php

namespace App\Repositorys;

use App\Models\Flashsale;
use App\Models\Orders;
use App\Models\Products;

class OrderRepositori
{
    public function orderCreate(int $customerId,float $total,array $orderDetails)
    {
        $order = Orders::create([
                'code' => uniqid(),
                'customer_id' => $customerId,
                'total' => $total,
            ]);

            // Simpan detail order
        foreach ($orderDetails as &$detail) {
            $detail['order_id'] = $order->id;
        }
        $order->details()->insert($orderDetails);

        return $order->load('customer', 'details');
    }

    public function getProduct(array $productId)
    {
        return Products::with('flashSale')
                        ->whereIn('id', $productId)
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');
    }
}
