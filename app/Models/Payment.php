<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'order_id'
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


}
