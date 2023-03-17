<?php
    namespace App\Traits;

    use App\Models\Coupon;
    use App\Exception\CartException;
    use Illuminate\Support\Facades\Event;
    use Illuminate\Support\Facades\Log;

    trait CartCouponsTrait {

        public static function bootCartCouponsTrait() {
            static::$addonFillable[] = 'coupon_code';

            Event::listen("cart.changed.coupon_code", function($order, $oldCouponCode) {
                $oldCouponCode = is_null($oldCouponCode)? 'NULL' : $oldCouponCode;
                $newCouponCode = is_null($order->coupon_code)? 'NULL' : $order->coupon_code;
                Log::info("Order #{$order->id} changed status from {$newCouponCode} to {$oldCouponCode}!");
            });
        }

        public function coupon() {
            if($couponCampaign = $this->couponCampaign())
                return $couponCampaign->owner();
        }

        public function removeCoupon() {
            $forceLoad = [];
            if($couponCampaign = $this->couponCampaign()) {
                $couponCampaign->forceDelete();
                $forceLoad[] = 'discounts';
            }
            if(($cartDelivery = $this->delivery()->first())) {
                if($couponCampaign = $cartDelivery->couponCampaign()) {
                    $couponCampaign->forceDelete();
                    $forceLoad[] = 'delivery.discounts';
                }
            }
            if(($cartPayment = $this->payment()->first())) {
                if($couponCampaign = $cartPayment->couponCampaign()) {
                    $couponCampaign->forceDelete();
                    $forceLoad[] = 'payment.discounts';
                }
            }
            $this->update([
                'coupon_code' => null
            ]);
            $this->refresh($forceLoad);
        }

        public function addCoupon($coupon, $attributes = []) {
            if($couponCampaign = $this->couponCampaign()) {
                if($couponCampaign->owner_id == $coupon->id)
                    return;
                $this->removeCoupon();
            }
            $campaign = $this->addDiscount(array_merge([
                'owner_id' => $coupon->id,
                'owner_type' => Coupon::class,
                'type' => $coupon->discount_type,
                'value' => $coupon->discount_value,
            ], $attributes));
            $campaign->setAvar('name', $coupon->aVar('name'));
            $forceLoad = ['discounts'];
            $this->loadMissing('delivery', 'payment');
            if(($cartDelivery = $this->delivery)) {
                $cartDelivery->addCoupon($coupon);
                $forceLoad[] = 'delivery.discounts';
            }
            if(($cartPayment = $this->payment)) {
                $cartPayment->addCoupon($coupon);
                $forceLoad[] = 'payment.discounts';
            }
            if($coupon->code) {
                $this->update([
                    'coupon_code' => $coupon->code
                ]);
            }
            $this->refresh($forceLoad);
        }

        public function couponCampaign() {
            return $this->discounts()->where('owner_type', Coupon::class)->first();
        }

        public function setCouponCode($couponCode) {
            if(($oldCouponCode = $this->coupon_code) == $couponCode)
                return;
            if(!$couponCode) {
                event( 'cart.changing.coupon_code', [$this, $couponCode] );
                $this->removeCoupon();
                event( 'cart.changed.coupon_code', [$this, $oldCouponCode] );
                return;
            }
            if(!($coupon = Coupon::whereCode($couponCode)->active()->first())) {
                throw new CartException( trans('marinar_coupons::exceptions.wrong_code'), 500);
                return false;
            }
            $this->attributes['coupon_code'] = $couponCode;

            if(!$coupon->check_discount($this)) {
                $this->attributes['coupon_code'] = $oldCouponCode;
                throw new CartException( trans('marinar_coupons::exceptions.check_false'), 500);
                return false;
            }
            event( 'cart.changing.coupon_code', [$this, $couponCode] );

            $this->addCoupon($coupon);

            event( 'cart.changed.coupon_code', [$this, $oldCouponCode] );
        }

        public function refreshCouponCode() {
            if(!($couponCode = $this->coupon_code)) {
                return;
            }
            if(!($coupon = Coupon::whereCode($couponCode)->active()->first()) || !$coupon->check_discount($this)) {
                event( 'cart.changing.coupon_code', [$this, null] );
                $this->removeCoupon();
                event( 'cart.changed.coupon_code', [$this, $couponCode] );
                return;
            }
            $this->addCoupon($coupon);
        }
    }
