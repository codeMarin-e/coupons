<?php
    namespace Marinar\Coupons;

    use Marinar\Coupons\Database\Seeders\MarinarCouponsInstallSeeder;

    class MarinarCoupons {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarCouponsInstallSeeder::class;
        }
    }
