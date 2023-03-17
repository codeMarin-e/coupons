<?php
return [
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'resources', 'views', 'components', 'admin', 'box_sidebar.blade.php']) => [
        "{{--  @HOOK_ADMIN_SIDEBAR  --}}" => "\t<x-admin.sidebar.coupons_option />\n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'config', 'marinar.php']) => [
        "// @HOOK_MARINAR_CONFIG_ADDONS" => "\t\t\\Marinar\\Coupons\\MarinarCoupons::class, \n"
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'config','marinar_orders.php']) => [
        "// @HOOK_ORDERS_CONFIGS_ADDONS" => "\t\t\\Marinar\\Coupons\\MarinarCoupons::class, \n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Models', 'Cart.php']) => [
        "// @HOOK_TRAITS" => "\tuse \\App\\Traits\\CartCouponsTrait; \n",
        "// @HOOK_REPRICE_END" => "\$this->refreshCouponCode();"
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Models', 'CartDelivery.php']) => [
        "// @HOOK_TRAITS" => "\tuse \\App\\Traits\\CartDeliveryCouponsTrait; \n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Models', 'CartPayment.php']) => [
        "// @HOOK_TRAITS" => "\tuse \\App\\Traits\\CartPaymentCouponsTrait; \n",
    ],
];
