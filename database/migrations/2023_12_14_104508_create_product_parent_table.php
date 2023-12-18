<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_parent', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('child_id')->nullable();
            $table->timestamps();

            // Define foreign key constraints if needed
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('products')->onDelete('set null');
            // Adjust 'products' above to the actual table name for products
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_parent');
    }
}
