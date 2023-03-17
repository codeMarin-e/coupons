@can('view', \App\Models\Coupon::class)
    {{-- COUPONS --}}
    <li class="nav-item @if(request()->route()->named("{$whereIam}.coupons.*")) active @endif">
        <a class="nav-link " href="{{route("{$whereIam}.coupons.index")}}">
            <i class="fa fa-fw fa-money-bill-wave mr-1"></i>
            <span>@lang("admin/coupons/coupons.sidebar")</span>
        </a>
    </li>
@endcan
