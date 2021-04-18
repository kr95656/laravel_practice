<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('primary_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_no');
            $table->timestamps();
        });

        Schema::create('secondary_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('primary_category_id');
            $table->string('name');
            $table->integer('sort_no');
            $table->timestamps();
            $table->foreign('primary_category_id')->references('id')->on('primary_categories');
        });

        Schema::create('item_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sort_no');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_condition_id');
            $table->unsignedBigInteger('secondary_category_id');
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('seller_id');

            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('price');
            $table->string('state');

            $table->timestamps();

            $table->foreign('secondary_category_id')->references('id')->on('primary_categories');
            $table->foreign('item_condition_id')->references('id')->on('item_conditions');
            $table->foreign('buyer_id')->references('id')->on('users');
            $table->foreign('seller_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 親テーブルから削除すること
        Schema::dropIfExists('items');
        Schema::dropIfExists('secondary_categories');
        Schema::dropIfExists('primary_categories');
        Schema::dropIfExists('item_conditions');
    }
}
