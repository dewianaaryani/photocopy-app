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
            $table->unsignedBigInteger('user_id');
            $table->decimal('total_price', 8, 2);
            //0 = pickup, 1 = delivery
            $table->string('type_delivery', 50)->default('pickup');
            $table->string('destination')->nullable();
            $table->integer('distance')->nullable();
            $table->tinyInteger('order_status')->default(0);
            $table->string('notes')->nullable();
            $table->string('payment_prove')->nullable();
            $table->tinyInteger('payment_status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
