@extends('layouts.app')

@section('title')
Orders
@endsection

@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Your Orders</h4>
                <!-- Breadcrumbs aligned to the very right -->
                <nav aria-label="breadcrumb" class="md:ml-auto">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        
                        <li class="breadcrumb-item active" aria-current="page">Order</li>
                    </ol>
                </nav>
            </div>
            <div class="card-body">
                @if(session('status'))
                    <div class="col-12">         
                        <div class="alert alert-success mt-1 mb-1">{{ session('status') }}</div>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Order Table</h4>
                                <div class="card-header-form">
                                    <form method="GET" action="{{ route('orders.index') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                                            <div class="input-group-btn">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>No</th>
                                            <th>Created At</th>
                                            <th>Order Status</th>
                                            <th>Payment Status</th>
                                            <th>Total Price</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach($orders as $index => $order)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>
                                                @if($order->order_status == 0)
                                                    <div class="badge badge-danger">Menunggu Pembayaran</div>
                                                @elseif($order->order_status == 1)
                                                    <div class="badge badge-warning">Sedang Diproses</div>
                                                @elseif($order->order_status == 2)
                                                    <div class="badge badge-secondary">Menunggu Dipickup</div>
                                                @else
                                                    <div class="badge badge-success">Completed</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->payment_status == 0)
                                                    <div class="badge badge-danger">Belum Dibayar</div>
                                                @elseif($order->payment_status == 1)
                                                    <div class="badge badge-warning">Menunggu validasi pembayaran</div>
                                                @else
                                                    <div class="badge badge-success">Sudah dibayar</div>
                                                @endif
                                            </td>
                                            <td>Rp. {{ number_format($order->total_price, 2)}}</td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">Detail</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                var itemId = this.getAttribute('data-item-id');
                var deleteForm = document.getElementById('delete-form');
                deleteForm.action = `/cart/delete/${itemId}`;
                deleteForm.submit();
            });
        });
    </script>
@endsection
@section('scripts')
    
@endsection