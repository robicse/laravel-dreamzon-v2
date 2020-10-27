<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="{!! asset('backend/user.png') !!}"
                                        alt="User Image" width="60px">
        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->roles[0]->name }}</p>
            <p class="app-sidebar__user-designation">Welcome To,<br/>{{ Auth::User()->name }}</p>
        </div>
    </div>
    <ul class="app-menu ">
        <li><a class="app-menu__item {{Request ::is('home') ? ' active ' : ''}}" href="{!! URL::to('/home') !!}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
        @php
            //if(Auth::User()->getRoleNames()[0] == "Admin"){
            //if(Auth::User()->role=='1'){
        @endphp
        <li class="treeview{{Request::is('productCategories*')||Request::is('productSubCategories*') ||Request::is('productBrands')||Request::is('products*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Products </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li  style="background-color: gray"><a class="app-menu__item" href="{{ route('productCategories.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Product Category</span></a></li>
                <li  style="background-color: gray"><a class="app-menu__item" href="{{ route('productSubCategories.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Product Sub Category</span></a></li>
                <li  style="background-color: gray"><a class="app-menu__item" href="{{ route('productBrands.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Product Brand</span></a></li>
                <li  style="background-color: gray"><a class="app-menu__item" href="{{ route('products.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Product</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('party*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Party </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('party.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Party List</span></a></li>
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('party.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Party Create</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('productPurchases*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Product Purchase </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productPurchases.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Purchase List</span></a></li>
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productPurchases.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Purchase Create</span></a></li>
{{--                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('product.pos.purchases.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Purchase POS</span></a></li>--}}

            </ul>
        </li>
        <li class="treeview{{Request::is('productPosSales*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">POS Sale </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productPosSales.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">List</span></a></li>
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productPosSales.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Create</span></a></li>
                {{--                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productPosSales.customer.due') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Customer Due</span></a></li>--}}
            </ul>
        </li>
        <li class="treeview{{Request::is('productSales*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Product Sale </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productSales.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale List</span></a></li>
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('productSales.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>
            </ul>
        </li>
{{--        <li class="treeview{{Request::is('productSales*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Product Sale </span><i class="treeview-indicator fa fa-angle-right"></i></a>--}}
{{--            <ul class="treeview-menu">--}}
{{--                <li><a class="app-menu__item" href="{{ route('productSales.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale List</span></a></li>--}}
{{--                <li><a class="app-menu__item" href="{{ route('productSales.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}
{{--            </ul>--}}
{{--        </li>--}}
        <li class="treeview{{Request::is('productSaleReturns*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Product Sale Return</span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li><a class="app-menu__item" href="{{ route('returnable.sale.product') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Returnable Sale Product </span></a></li>
                <li><a class="app-menu__item" href="{{ route('productSaleReturns.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Return List</span></a></li>
{{--                <li><a class="app-menu__item" href="{{ route('productSaleReturns.create') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Product Sale Create</span></a></li>--}}

            </ul>
        </li>
        <li class="treeview{{Request::is('stock*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Stock </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('stock.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Stock List</span></a></li>
            </ul>
        </li>
        <li class="treeview{{Request::is('transaction*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Transaction </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('transaction.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Transaction List</span></a></li>
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('delivery.index') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Delivery Charge List</span></a></li>
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('transaction.lossProfit') }}"><i class="app-menu__icon fas fa-users"></i> <span class="app-menu__label">Loss/Profit</span></a></li>
            </ul>
        </li>
        <hr/>
        <li><a class="app-menu__item" {{Request ::is('/users')  ? ' active ' : ''}} href="{{ route('users.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Users</span></a></li>
        <li><a class="app-menu__item" href="{{ route('stores.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Stores</span></a></li>
        <li class="treeview{{Request::is('roles*') ? ' is-expanded': ''}}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">Role Permissions </span><i class="treeview-indicator fa fa-angle-right"></i></a>
            <ul class="treeview-menu">
                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('roles.index') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Role Permission List</span></a></li>
{{--                <li style="background-color: gray"><a class="app-menu__item" href="{{ route('roles.create') }}"><i class="app-menu__icon fas fa-users"></i><span class="app-menu__label">Create Role Permission</span></a></li>--}}
            </ul>
        </li>
        @php
            //}
        @endphp
    </ul>
</aside>
