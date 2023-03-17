@php $inputBag = 'coupon'; @endphp

@pushonce('below_templates')
@if(isset($chCoupon) && $authUser->can('delete', $chCoupon))
    <form action="{{ route("{$route_namespace}.coupons.destroy", $chCoupon) }}"
          method="POST"
          id="delete[{{$chCoupon->id}}]">
        @csrf
        @method('DELETE')
    </form>
@endif
@endpushonce

{{-- @HOOK_AFTER_PUSHES --}}

<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route("{$route_namespace}.coupons.index") }}">@lang('admin/coupons/coupons.coupons')</a></li>
            <li class="breadcrumb-item active">@isset($chCoupon){{ $chCoupon->aVar('name') }}@else @lang('admin/coupons/coupon.create') @endisset</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="@isset($chCoupon){{ route("{$route_namespace}.coupons.update", [ $chCoupon->id ]) }}@else{{ route("{$route_namespace}.coupons.store") }}@endisset"
                      method="POST"
                      autocomplete="off"
                      enctype="multipart/form-data">
                    @csrf
                    @isset($chCoupon)@method('PATCH')@endisset

                    <x-admin.box_messages />

                    <x-admin.box_errors :inputBag="$inputBag" />
                    {{-- @HOOK_BEGINING --}}


                    <div class="form-group row">
                        <label for="{{$inputBag}}[add][name]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/coupons/coupon.name'):</label>
                        <div class="col-lg-10">
                            <input type="text"
                                   name="{{$inputBag}}[add][name]"
                                   id="{{$inputBag}}[add][name]"
                                   value="{{ old("{$inputBag}.add.name", (isset($chCoupon)? $chCoupon->aVar('name') : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('add.name')) is-invalid @endif"
                            />
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_NAME --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[code]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/coupons/coupon.code'):</label>
                        <div class="col-lg-3">
                            <input type="text"
                                   name="{{$inputBag}}[code]"
                                   id="{{$inputBag}}[code]"
                                   value="{{ old("{$inputBag}.code", (isset($chCoupon)? $chCoupon->code : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('code')) is-invalid @endif"
                            />
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_CODE --}}

                    @php
                        $discount_value = old("{$inputBag}.discount_value", (isset($chCoupon)?  $chCoupon->discount_value : 0));
                        $discount_type = old("{$inputBag}.discount_type", (isset($chCoupon)?  $chCoupon->discount_type : current($discountTypes)));
                    @endphp
                    <div class="form-group row">
                        <label for="{{$inputBag}}[discount_value]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/coupons/coupon.discount.label'):</label>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <input type="text"
                                       placeholder="0"
                                       name="{{$inputBag}}[discount_value]"
                                       value="{{$discount_value}}"
                                       class="form-control @if($errors->{$inputBag}->has("discount_value")) is-invalid @endif" />
                                <div class="input-group-append">
                                    <select class="form-control @if($errors->{$inputBag}->has("discount_type")) is-invalid @endif"
                                            name="{{$inputBag}}[discount_type]">
                                        @foreach($discountTypes as $type => $typeName)
                                            <option value="{{$type}}"
                                                    @if($type == $discount_type)selected="selected"@endif
                                            >{{$typeName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- @HOOK_AFTER_DISCOUNT --}}

                        <div class="col-lg-6">
                            <div class="form-check align-middle">
                                <input type="checkbox"
                                       value="1"
                                       id="{{$inputBag}}[free_taxes]"
                                       name="{{$inputBag}}[free_taxes]"
                                       class="form-check-input @if($errors->$inputBag->has('free_taxes'))is-invalid @endif"
                                       @if(old("{$inputBag}.free_taxes") || (is_null(old("{$inputBag}.free_taxes")) && isset($chCoupon) && $chCoupon->free_taxes ))checked="checked"@endif
                                />
                                <label class="form-check-label"
                                       for="{{$inputBag}}[free_taxes]">@lang('admin/coupons/coupon.free_taxes')</label>
                            </div>
                        </div>
                        {{-- @HOOK_AFTER_FREE_TAXES --}}
                    </div>

                    {{-- @HOOK_AFTER_DISCOUNT_FIELDS --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[min_total]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/coupons/coupon.min_total'):</label>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <input type="text"
                                       name="{{$inputBag}}[min_total]"
                                       id="{{$inputBag}}[min_total]"
                                       value="{{ old("{$inputBag}.min_total", (isset($chCoupon)? $chCoupon->min_total : '0')) }}"
                                       class="form-control @if($errors->$inputBag->has('min_total')) is-invalid @endif"
                                />
                                <div class="input-group-append">
                                    <span class="input-group-text">{{$siteCurrency}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_MIN_TOTAL --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[max_total]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/coupons/coupon.max_total'):</label>
                        <div class="col-lg-2">
                            <div class="input-group">
                                <input type="text"
                                       name="{{$inputBag}}[max_total]"
                                       id="{{$inputBag}}[max_total]"
                                       value="{{ old("{$inputBag}.max_total", (isset($chCoupon)? $chCoupon->max_total : '0')) }}"
                                       class="form-control @if($errors->$inputBag->has('max_total')) is-invalid @endif"
                                />
                                <div class="input-group-append">
                                    <span class="input-group-text">{{$siteCurrency}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_MAX_TOTAL --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[max_orders]"
                               class="col-lg-2 col-form-label"
                        >@lang('admin/coupons/coupon.max_orders'):</label>
                        <div class="col-lg-2">
                            <input type="text"
                                   name="{{$inputBag}}[max_orders]"
                                   id="{{$inputBag}}[max_orders]"
                                   value="{{ old("{$inputBag}.max_orders", (isset($chCoupon)? $chCoupon->max_orders : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('max_orders')) is-invalid @endif"
                            />
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_MAX_ORDERS --}}

                    <x-admin.activiable
                        :inputBag="$inputBag"
                        :activiable="$chCoupon?? null"
                        translations="admin/coupons/coupon.activiable"
                    />
                    {{-- @HOOK_AFTER_ACTIVIABLE --}}

                    <div class="form-group row">
                        @isset($chCoupon)
                            @can('update', $chCoupon)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='action'>@lang('admin/coupons/coupon.save')
                                </button>

                                <button class='btn btn-primary mr-2'
                                        type='submit'
                                        name='update'>@lang('admin/coupons/coupon.update')</button>
                            @endcan

                            @can('delete', $chCoupon)
                                <button class='btn btn-danger mr-2'
                                        type='button'
                                        onclick="if(confirm('@lang("admin/coupons/coupon.delete_ask")')) document.querySelector( '#delete\\[{{$chCoupon->id}}\\] ').submit() "
                                        name='delete'>@lang('admin/coupons/coupon.delete')</button>
                            @endcan
                        @else
                            @can('create', \App\Models\Coupon::class)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='create'>@lang('admin/coupons/coupon.create')</button>
                            @endcan
                        @endisset

                        {{-- @HOOK_AFTER_BUTTONS --}}

                        <a class='btn btn-warning'
                           href="{{ route("{$route_namespace}.coupons.index") }}"
                        >@lang('admin/coupons/coupon.cancel')</a>
                    </div>

                    {{-- @HOOK_ADDON_BUTTONS --}}

                </form>
            </div>
        </div>

        {{-- @HOOK_ADDONS --}}
    </div>
</x-admin.main>
