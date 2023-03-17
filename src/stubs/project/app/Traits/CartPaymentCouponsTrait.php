<?php
    namespace App\Traits;

    use App\Models\Coupon;

    trait CartPaymentCouponsTrait {

        public function coupon() {
            if($couponCampaign = $this->couponCampaign())
                return $couponCampaign->owner();
        }

        public function addCoupon($coupon, $attributes = []) {
            if(!$coupon->free_taxes)
                return;
            if($couponCampaign = $this->couponCampaign()) {
                $couponCampaign->delete();
            }
            $campaign = $this->addDiscount(array_merge([
                'owner_id' => $coupon->id,
                'owner_type' => Coupon::class,
                'type' => 'PERCENT',
                'value' => 100,
            ], $attributes));

            $campaign->setAvar('name', $coupon->aVar('name'));
        }

        public function couponCampaign() {
            return $this->discounts()->where('owner_type', Coupon::class)->first();
        }
    }
