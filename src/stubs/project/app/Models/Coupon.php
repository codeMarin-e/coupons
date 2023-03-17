<?php
    namespace App\Models;

    use App\Traits\AddVariable;
    use Illuminate\Database\Eloquent\Model;
    use App\Traits\Activiable;
    use App\Traits\MacroableModel;

    class Coupon extends Model {

        protected $fillable = [
            'site_id', 'code', 'max_orders', 'min_total', 'max_total',
            'free_taxes', 'period_type', 'period_from', 'period_to',
            'discount_value', 'discount_type'
        ];

        protected $casts = [
            'period_from' => 'datetime',
            'period_to' => 'datetime',
        ];

        use MacroableModel;
        use AddVariable;
        use Activiable;

        // @HOOK_TRAITS

        public function check_discount($order = null) {
            $order = $order?? app()->make('Cart');
            if(!$order) return false;
            if($order->coupon_code != $this->code) return false;
            if(!$this->active) return false;
            $orderTotal = $order->getTotalPrice();
            if($this->min_total && $this->min_total > $orderTotal) return false;
            if($this->max_total && $this->max_total < $orderTotal) return false;
            if($this->max_orders && $this->max_orders < $this->ordersCount() ) return false;
            return true;
        }

        public function ordersCount() {
            return Cart::whereCoupon_code($this->code)
                //DEPENDING FROM SITE LOGIC
//            ->where(function($qry) {
//                $qry->where('confirmed_at', '!=', null)
//                    ->orWhere('processing_from', '!=', null);
//            })
                ->count();
        }
    }
