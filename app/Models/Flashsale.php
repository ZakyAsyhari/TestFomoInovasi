<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Flashsale extends Model
{
    protected $table = 'flashsales';

    protected $guarded = ['id'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];


    public function product()
    {
        return $this->belongsTo(Products::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function isActive()
    {
        if (is_null($this->start_date) || is_null($this->end_date)) {
            return false;
        }
        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    public function remainingQuota()
    {
        return max(0, $this->qty - $this->sold_qty);
    }
}
