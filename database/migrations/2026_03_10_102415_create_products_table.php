<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('barcode')->unique();
            $table->foreignId('product_type_id')->constrained('product_type', 'id')->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained('supplier', 'id')->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->decimal('sell_price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
