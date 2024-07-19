@extends('layouts.app')

@section('title')
Orders
@endsection

@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header"><h4>Your Orders</h4></div>
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
                                    <!-- You can add search or filter form here if needed -->
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
                                            <td>Rp. {{ number_format($order->total_price, 2) }}</td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">Detail</a>
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
@endsection

@section('scripts')
    <!-- Add any custom scripts if needed -->
@endsection
