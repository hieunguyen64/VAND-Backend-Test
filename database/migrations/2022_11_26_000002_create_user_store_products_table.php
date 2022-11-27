<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStoreProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('user_store_products', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('store_id');
            $table->foreign('store_id')
                ->references('id')
                ->on('user_stores')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('name');
            $table->double('price');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists("user_store_products");
    }
}