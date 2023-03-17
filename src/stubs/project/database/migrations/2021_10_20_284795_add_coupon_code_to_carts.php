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
    public function up() {
        if(!Schema::hasTable('carts')) return;
        Schema::table('carts', function (Blueprint $table) {
            $table->string('coupon_code', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (!Schema::hasTable('carts')) return;
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['coupon_code']);
        });
    }
};
