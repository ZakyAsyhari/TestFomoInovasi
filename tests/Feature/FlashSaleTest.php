<?php

namespace Tests\Feature;

use App\Models\Customers;
use App\Models\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlashSaleTest extends TestCase
{
    use refreshDatabase;

    public function test_flashsale()
    {
        $customer = Customers::create([
            'name' => 'John Doe',
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
        ]);

        $product = Products::create([
            'code' => fake()->unique()->ean13(),
            'name' => fake()->words(3, true),
            'price' => 100000,
            'stock' => 10,
        ]);

        // QTY pada flash sale adalah jumlah customers yang bisa mendapatkan harga flash sale
        $flashSale = $product->flashSale()->create([
            'code' => fake()->unique()->ean13(),
            'product_id' => $product->id,
            'flashsale_price' => 50000,
            'qty' => 5,
            'start_date' => now()->subMinute(),
            'end_date' => now()->addHours(),
        ]);

        $responses = [];
        for ($i=0; $i < 10 ; $i++) {
            $responses[] = $this->postJson('/api/order', [
                'customer_id' => $customer->id,
                'items' => [
                    [
                        'product_id' => $product->id,
                        'qty' => 1,
                    ],
                ],
            ]);
        }


        $product->refresh();
        $flashSale->refresh();
        $successCount = collect($responses)
            ->filter(fn ($response) => $response->status() === 200)
            ->count();

        $this->assertEquals(10, $successCount);
        $this->assertEquals(0, $product->stock);


        $this->assertEquals(5, $flashSale->sold_qty);

        $discountOrders = 0;
        $normalOrders = 0;

        foreach ($responses as $response) {
            $order = $response->json('data')['data'];
            $price = $order['details'][0]['price'];
            if ($price == 50000) {
                $discountOrders++;
            } else {
                $normalOrders++;
            }
        }

        $this->assertEquals(5, $discountOrders);
        $this->assertEquals(5, $normalOrders);
    }
}
