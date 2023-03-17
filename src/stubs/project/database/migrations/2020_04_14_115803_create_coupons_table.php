<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('site_id');
            $table->string('code');
            $table->integer('max_orders')->default(0);
            $table->integer('min_total')->default(0);
            $table->integer('max_total')->default(0);
            $table->string('discount_type');
            $table->float('discount_value')->default(0);
            $table->boolean('free_taxes')->default(false);
            $table->boolean('period_type')->default(false);
            $table->timestamp('period_from', 0)->nullable();
            $table->timestamp('period_to', 0)->nullable();
            $table->timestamps();
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
