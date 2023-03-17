<?php
    namespace Marinar\Coupons\Database\Seeders;

    use App\Models\PaymentMethod;
    use Illuminate\Database\Seeder;
    use Marinar\Coupons\MarinarCoupons;
    use Spatie\Permission\Models\Permission;

    class MarinarCouponsRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_coupons';
            static::$packageDir = MarinarCoupons::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoRemove();

            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                foreach(PaymentMethod::get() as $payment) {
                    $payment->delete();
                }
                Permission::whereIn('name', [
                    'coupons.view',
                    'coupon.create',
                    'coupon.update',
                    'coupon.delete',
                ])
                ->where('guard_name', 'admin')
                ->delete();
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                return true;
            });
        }
    }
