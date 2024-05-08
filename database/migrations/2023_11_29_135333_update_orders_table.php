<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_status')->default('Commande_passée')->change();
            $table->string('payment_status')->default('En attente de paiment')->change();
            $table->string('shipping_delivery_type')->default('Régulier')->comment('Régulier/planifié')->change();
        });
    }

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_status')->default('Commande_passée')->change();
            $table->string('payment_status')->default('En attente de paiment')->change();
            $table->string('shipping_delivery_type')->default('regular')->comment('regular/scheduled')->change();
            
        });
    }
}
