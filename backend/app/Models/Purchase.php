<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;
    protected $fillable = ['supplier'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'unit_price');
    }
}
