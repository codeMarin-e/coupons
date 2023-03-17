<?php
use App\Models\Coupon;
use App\Policies\CouponPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::model('chCoupon', Coupon::class);
Gate::policy(Coupon::class, CouponPolicy::class);

