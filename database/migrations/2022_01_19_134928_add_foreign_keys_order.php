<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            //relasi dari order ke service
            $table->foreign('service_id', 'fk_order_to_service')->references('id')
            ->on('service')->onUpdate('CASCADE')->onDelete('CASCADE');

            //relasi dari order freelancer ke users
            $table->foreign('freelancer_id', 'fk_order_freelancer_to_users')->references('id')
            ->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');

            //relasi dari order buyer ke users
            $table->foreign('buyer_id', 'fk_order_buyer_to_users')->references('id')
            ->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');

            //relasi dari order ke order status
            $table->foreign('order_status_id', 'fk_order_to_order_status')->references('id')
            ->on('order_status')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropForeign('fk_order_to_service');
            $table->dropForeign('fk_order_freelancer_to_users');
            $table->dropForeign('fk_order_buyer_to_users');
            $table->dropForeign('fk_order_to_order_status');
        });
    }
}
