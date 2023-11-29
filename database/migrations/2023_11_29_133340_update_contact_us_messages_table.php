<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContactUsMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_us_messages', function (Blueprint $table) {
            $table->string('support_for')->comment('Problème_de_livraison | Service_client | Autres_services')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_us_messages', function (Blueprint $table) {
            $table->string('support_for')->comment('delivery_problem | customer_service | other_service')->change();
        });
    }
}
