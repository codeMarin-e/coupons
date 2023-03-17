<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\Coupons\Database\Seeders\MarinarCouponsInstallSeeder"',
		],
		'remove' => [
            'php artisan db:seed --class="\Marinar\Coupons\Database\Seeders\MarinarCouponsRemoveSeeder"',
        ]
	];
