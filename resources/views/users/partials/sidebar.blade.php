<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    @if (Auth::user()->type == 'admin')
      <a href="{{ route('admin.home') }}">Photocopy</a>
    @else
      <a href="{{ route('home') }}">Photocopy</a>
    @endif
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="/">PC</a>
  </div>
  <ul class="sidebar-menu">

    <!-- Admin-specific features -->
    @if(auth()->user()->type == 'admin')
      <li class="menu-header">Dashboard</li>
      <li class="dropdown">
        <a href="{{route('admin.home')}}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
      </li>
      <li class="menu-header">Orders</li>
      <li class="dropdown">
        <a href="{{route('admin.orders.index')}}" class="nav-link"><i class="fas fa-list"></i><span>Orders</span></a>
      </li>
      {{-- <li class="menu-header">Categories</li>
      <li class="dropdown">
        <a href="{{route('admin.categories.index')}}" class="nav-link"><i class="fas fa-th-list"></i><span>Categories</span></a>
      </li>
      <li class="menu-header">Products</li>
      <li class="dropdown">
        <a href="{{route('admin.products.index')}}" class="nav-link"><i class="fas fa-boxes"></i><span>Products</span></a>
      </li> --}}
    @else
      <li class="menu-header">Dashboard</li>
      <li class="dropdown">
        <a href="{{route('home')}}" class="nav-link"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
      </li>
      <li class="menu-header">Features</li>
      <li class="dropdown">
        <a href="{{route('photocopy.add')}}" class="nav-link"><i class="fas fa-copy"></i><span>Photocopy</span></a>
      </li>
      <li class="dropdown">
        <a href="{{route('printout.add')}}" class="nav-link"><i class="fas fa-print"></i><span>Print Out</span></a>
      </li>
      <li class="dropdown">
        <a href="{{route('cetakfoto.add')}}" class="nav-link"><i class="fas fa-camera"></i><span>Cetak Foto</span></a>
      </li>
      {{-- <li class="dropdown">
        <a href="{{route('products.index')}}" class="nav-link"><i class="fas fa-box-open"></i><span>Products</span></a>
      </li> --}}
      <li class="menu-header">Orders</li>
      <li class="dropdown">
        <a href="{{route('cart.index')}}" class="nav-link"><i class="fas fa-shopping-cart"></i><span>My Carts</span></a>
      </li>
      <li class="dropdown">
        <a href="{{route('orders.index')}}" class="nav-link"><i class="fas fa-list"></i><span>My Orders</span></a>
      </li>
    @endif

  </ul>
</aside>
