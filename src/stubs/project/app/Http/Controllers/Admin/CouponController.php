<?php

    namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\View;
    use Illuminate\Support\Str;
    use App\Http\Requests\Admin\CouponRequest;
    use App\Models\Coupon;
    use App\Models\Discount;

    class CouponController extends Controller {

        public function __construct() {
            if(!request()->route()) return;
            $this->table = Coupon::getModel()->getTable();
            $this->routeNamespace = Str::before(request()->route()->getName(), '.coupons');
            View::composer('admin/coupons/*', function($view) {
                $viewData = [
                    'route_namespace' => $this->routeNamespace,
                ];
                // @HOOK_VIEW_COMPOSERS
                $view->with($viewData);
            });
            // @HOOK_CONSTRUCT
        }

        public function index() {
            $viewData = [];
            $viewData['coupons'] = Coupon::where("{$this->table}.site_id", app()->make('Site')->id)
                ->orderBy("{$this->table}.id", 'ASC');

            // @HOOK_INDEX

            $viewData['coupons'] = $viewData['coupons']->paginate(20)->appends( request()->query() );

            return view('admin/coupons/coupons', $viewData);
        }

        public function create() {
            $viewData = [];
            $discountTypes = [];
            foreach(Discount::$types as $type) {
                $discountTypes[$type] = trans("admin/coupons/coupon.discount.type.{$type}");
            }
            // @HOOK_CREATE

            $viewData['discountTypes'] = $discountTypes;
            return view('admin/coupons/coupon', $viewData);
        }

        public function edit(Coupon $chCoupon) {
            $viewData['chCoupon'] = $chCoupon;
            $discountTypes = [];
            foreach(Discount::$types as $type) {
                $discountTypes[$type] = trans("admin/coupons/coupon.discount.type.{$type}");
            }
            // @HOOK_EDIT

            $viewData['discountTypes'] = $discountTypes;
            return view('admin/coupons/coupon', $viewData);
        }

        public function store(CouponRequest $request) {
            $validatedData = $request->validated();

            // @HOOK_STORE_VALIDATE

            $coupon = Coupon::create( array_merge([
                'site_id' => app()->make('Site')->id,
            ], $validatedData));

            // @HOOK_STORE_INSTANCE

            $coupon->setAVars($validatedData['add']);

            // @HOOK_STORE_END
            event( 'coupon.submited', [$coupon, $validatedData] );
            return redirect()->route($this->routeNamespace.'.coupons.edit', $coupon)
                ->with('message_success', trans('admin/coupons/coupon.created'));
        }

        public function update(Coupon $chCoupon, CouponRequest $request) {
            $validatedData = $request->validated();

            // @HOOK_UPDATE_VALIDATE

            $chCoupon->setAVars($validatedData['add']);
            $chCoupon->update( $validatedData ); //second type of validation
            event( 'coupon.submited', [$chCoupon, $validatedData] );

            // @HOOK_UPDATE_END

            if($request->has('action')) {
                return redirect()->route($this->routeNamespace.'.coupons.index')
                    ->with('message_success', trans('admin/coupons/coupon.updated'));
            }
            return back()->with('message_success', trans('admin/coupons/coupon.updated'));
        }

        public function destroy(Coupon $chCoupon, Request $request) {
            // @HOOK_DESTROY

            $chCoupon->delete();

            // @HOOK_DESTROY_END

            if($request->redirect_to)
                return redirect()->to($request->redirect_to)
                    ->with('message_danger', trans('admin/coupons/coupon.deleted'));

            return redirect()->route($this->routeNamespace.'.coupons.index')
                ->with('message_danger', trans('admin/coupons/coupon.deleted'));
        }
    }
