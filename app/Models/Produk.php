<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'barcode',
        'price',
        'qty',
        'status'
    ];

    public function orders()
    {
        return $this->hasMany(OrderDetail::class, 'produk_id');
    }

    public function scopeFilter($query, array $filters) {
        // dd($filters['request']['gender']);
        $query->when($filters['request']['name'] ?? false, function ($query, $value) {
            return $query->where('name', 'like', "%{$value}%");
        });
    }
}
