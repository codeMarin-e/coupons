<?php
    namespace Marinar\Coupons\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Coupons\MarinarCoupons;

    class MarinarCouponsInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_coupons';
            static::$packageDir = MarinarCoupons::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
