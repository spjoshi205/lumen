<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('UserID');
            $table->enum('OrderStatus', array('pending', 'accepted', 'processed'))->default('pending');
            $table->string('Address');
            $table->string('City');
            $table->string('Postcode');
            $table->string('Street');
            $table->string('Province');
            $table->string('Country');
            $table->longText('OrderDetail');
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
        Schema::drop('Orders');
    }
}
