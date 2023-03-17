<?php

namespace App\Policies;

use App\Models\Coupon;
use App\Models\User;

class CouponPolicy
{
    public function before(User $user, $ability) {
        // @HOOK_POLICY_BEFORE
        if($user->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function view(User $user) {
        // @HOOK_POLICY_VIEW
        return $user->hasPermissionTo('coupons.view', request()->whereIam());
    }

    public function create(User $user) {
        // @HOOK_POLICY_CREATE
        return $user->hasPermissionTo('coupon.create', request()->whereIam());
    }

    public function update(User $user, Coupon $chCoupon) {
        // @HOOK_POLICY_UPDATE
        if( !$user->hasPermissionTo('coupon.update', request()->whereIam()) )
            return false;
        return true;
    }

    public function delete(User $user, Coupon $chCoupon) {
        // @HOOK_POLICY_DELETE
        if( !$user->hasPermissionTo('coupon.delete', request()->whereIam()) )
            return false;
        return true;
    }

    // @HOOK_POLICY_END
}
