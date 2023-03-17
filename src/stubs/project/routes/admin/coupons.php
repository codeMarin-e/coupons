<?php

use App\Http\Controllers\Admin\CouponController;
use App\Models\Coupon;

Route::group([
    'controller' => CouponController::class,
    'middleware' => ['auth:admin', 'can:view,'.Coupon::class],
    'as' => 'coupons.', //naming prefix
    'prefix' => 'coupons', //for routes
], function() {
    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store')->middleware('can:create,'.Coupon::class);
    Route::get('create', 'create')->name('create')->middleware('can:create,'.Coupon::class);
    Route::get('{chCoupon}/edit', 'edit')->name('edit');

    // @HOOK_ROUTES_MODEL

    Route::get('{chCoupon}', 'edit')->name('show');
    Route::patch('{chCoupon}', 'update')->name('update')->middleware('can:update,chCoupon');
    Route::delete('{chCoupon}', 'destroy')->name('destroy')->middleware('can:delete,chCoupon');

    // @HOOK_ROUTES
});
