<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;
    protected $fillable = [
        'customer',
        'profit',
        'cancelled_at'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'unit_price');;
    }
}
