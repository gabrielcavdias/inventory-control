<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'suggested_price',
        'average_cost',
        'stock_quantity',
    ];

    public function sales()
    {
        return  $this->belongsToMany(Sale::class);
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class);
    }
}
