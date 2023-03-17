<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item active">@lang('admin/coupons/coupons.coupons')</li>
        </ol>

        @can('create', App\Models\Coupon::class)
            <a href="{{ route("{$route_namespace}.coupons.create") }}"
               class="btn btn-sm btn-primary h5"
               title="create">
                <i class="fa fa-plus mr-1"></i>@lang('admin/coupons/coupons.create')
            </a>
        @endcan

        {{-- @HOOK_AFTER_CREATE --}}

        <x-admin.box_messages />

        <div class="table-responsive rounded ">
            <table class="table table-sm">
                <thead class="thead-light">
                <tr class="">
                    <th scope="col" class="text-center">@lang('admin/coupons/coupons.id')</th>
                    {{-- @HOOK_AFTER_ID_TH --}}

                    <th scope="col" class="w-65">@lang('admin/coupons/coupons.name')</th>
                    {{-- @HOOK_AFTER_NAME_TH --}}

                    <th scope="col" class="text-center">@lang('admin/coupons/coupons.code')</th>
                    {{-- @HOOK_AFTER_CODE_TH --}}

                    <th scope="col" class="text-center">@lang('admin/coupons/coupons.edit')</th>
                    {{-- @HOOK_AFTER_EDIT_TH --}}

                    <th scope="col" class="text-center">@lang('admin/coupons/coupons.remove')</th>
                    {{-- @HOOK_AFTER_REMOVE_TH --}}
                </tr>
                </thead>
                <tbody>
                @forelse($coupons as $coupon)
                    @php
                        $couponEditUri = route("{$route_namespace}.coupons.edit", $coupon->id);
                    @endphp
                    <tr>
                        <td scope="row" class="text-center align-middle"><a href="{{ $couponEditUri }}"
                                                                            title="@lang('admin/coupons/coupons.edit')"
                            >{{ $coupon->id }}</a></td>
                        {{-- @HOOK_AFTER_ID --}}

                        {{--    NAME    --}}
                        <td class="w-75 align-middle">
                            <a href="{{ $couponEditUri }}"
                               title="@lang('admin/coupons/coupons.edit')"
                               class="@if($coupon->active) @else text-danger @endif"
                            >{{ \Illuminate\Support\Str::words($coupon->aVar('name'), 12,'....') }}</a>
                        </td>
                        {{-- @HOOK_AFTER_NAME --}}

                        {{--    Code    --}}
                        <td class="text-center align-middle">{{$coupon->code}}</td>
                        {{-- @HOOK_AFTER_CODE --}}

                        {{--    EDIT    --}}
                        <td class="text-center">
                            <a class="btn btn-link text-success"
                               href="{{ $couponEditUri }}"
                               title="@lang('admin/coupons/coupons.edit')"><i class="fa fa-edit"></i></a></td>
                        {{-- @HOOK_AFTER_EDIT--}}

                        {{--    DELETE    --}}
                        <td class="text-center">
                            @can('delete', $coupon)
                                <form action="{{ route("{$route_namespace}.coupons.destroy", [$coupon]) }}"
                                      method="POST"
                                      id="delete[{{$coupon->id}}]">
                                    @csrf
                                    @method('DELETE')
                                    @php
                                        $redirectTo = (!$coupons->onFirstPage() && $coupons->count() == 1)?
                                                $coupons->previousPageUrl() :
                                                url()->full();
                                    @endphp
                                    <button class="btn btn-link text-danger"
                                            title="@lang('admin/coupons/coupons.remove')"
                                            onclick="if(confirm('@lang("admin/coupons/coupons.remove_ask")')) document.querySelector( '#delete\\[{{$coupon->id}}\\] ').submit() "
                                            type="button"><i class="fa fa-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                        {{-- @HOOK_AFTER_REMOVE --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">@lang('admin/coupons/coupons.no_coupons')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{$coupons->links('admin.paging')}}

        </div>
    </div>
</x-admin.main>
