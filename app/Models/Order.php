<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'code', 'nama_customer', 'nomor_meja'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function scopeFilter($query, array $filters) {
        // dd($filters['request']['gender']);
        $query->when($filters['request']['code'] ?? false, function ($query, $value) {
            return $query->where('code', 'like', "%{$value}%");
        });
    }

}
