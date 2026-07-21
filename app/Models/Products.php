<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    /** @use HasFactory<\Database\Factories\ProductsFactory> */
    use HasFactory;

    protected $table = 'products';

    protected $guarded = ['id'];


    public function flashSale()
    {
        return $this->hasOne(Flashsale::class, 'product_id', 'id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function getCurrentPrice()
    {
        $flashsale = $this->flashSale;
        if($flashsale && Carbon::now()->between($flashsale->start_date, $flashsale->end_date)) {
            $this->price = $flashsale->flashsale_price;
        }

        return $this->price;
    }
}
