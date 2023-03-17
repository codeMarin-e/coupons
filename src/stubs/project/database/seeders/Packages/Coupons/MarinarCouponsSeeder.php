<?php
namespace Database\Seeders\Packages\Coupons;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MarinarCouponsSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::upsert([
            ['guard_name' => 'admin', 'name' => 'coupons.view'],
            ['guard_name' => 'admin', 'name' => 'coupon.create'],
            ['guard_name' => 'admin', 'name' => 'coupon.update'],
            ['guard_name' => 'admin', 'name' => 'coupon.delete'],
        ], ['guard_name','name']);
    }
}
