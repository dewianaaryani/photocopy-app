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
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');         
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->string('file_pdf', 100)->nullable();
            $table->integer('quantity');
            $table->integer('number_of_page')->nullable();
            $table->integer('selected_number_of_page')->nullable();
            $table->unsignedInteger('additional_id')->nullable();
            $table->integer('price');
            $table->timestamps();
        

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');            
            $table->foreign('additional_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
