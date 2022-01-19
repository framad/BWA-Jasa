<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();

            // $table->integer('service_id')->nullable();
            // $table->integer('freelancer_id')->nullable();
            // $table->integer('buyer_id')->nullable();

            $table->ForeignId('service_id')->nullable()->index('fk_order_to_service');
            $table->ForeignId('freelancer_id')->nullable()->index('fk_order_freelancer_to_users');
            $table->ForeignId('buyer_id')->nullable()->index('fk_order_buyer_to_users');

            $table->longText('file')->nullable();
            $table->longText('note')->nullable();
            $table->date('expired')->nullable();

            //$table->integer('order_status_id')->nullable();
            $table->ForeignId('order_status_id')->nullable()->index('fk_order_to_order_status');

            $table->softDeletes();
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
        Schema::dropIfExists('order');
    }
}
