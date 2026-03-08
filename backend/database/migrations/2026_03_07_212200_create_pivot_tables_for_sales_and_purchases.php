<?php

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_purchase', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->foreignIdFor(Purchase::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer('unit_price');
            $table->integer('quantity');
        });

        Schema::create('product_sale', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->foreignIdFor(Sale::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer('unit_price');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_purchase');
        Schema::dropIfExists('product_sale');
    }
};
