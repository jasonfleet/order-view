<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->decimal('bulk_total', 8, 2);
            $table->string('contact_name');
            $table->string('date');
            $table->string('delivery_address_1');
            $table->string('delivery_address_2');
            $table->string('delivery_address_3');
            $table->string('delivery_town');
            $table->string('delivery_county');
            $table->string('delivery_postcode');
            $table->string('email_address');
            $table->string('name');
            $table->foreignId('order_id');
            $table->foreignId('organization_id');
            $table->string('telephone');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
