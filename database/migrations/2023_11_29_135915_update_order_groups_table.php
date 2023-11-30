<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the columns don't exist before adding them
        if (!Schema::hasColumn('order_groups', 'payment_method')) {
            Schema::table('order_groups', function (Blueprint $table) {
                $table->string('payment_method')->default('paiement_à_la_livraison');
            });
        }

        if (!Schema::hasColumn('order_groups', 'payment_status')) {
            Schema::table('order_groups', function (Blueprint $table) {
                $table->string('payment_status')->default('impayé');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback the changes
        Schema::table('order_groups', function (Blueprint $table) {
            $table->string('payment_method')->default('cash_on_delivery');
            $table->string('payment_status')->default('unpaid');
        });
    }
}
