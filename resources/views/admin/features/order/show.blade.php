@extends('layouts.app')

@section('title')
Orders
@endsection

@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header"><h4>Order Details</h4></div>
            <div class="card-body">
                <div class="row mb-4">
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
                </div>
                <div class="section-body">
                    <div class="invoice">
                      <div class="invoice-print">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="invoice-title">
                              <h2>Order</h2>
                              <div class="invoice-number">Order #{{$order->id}}</div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-md-6">
                                <address>
                                @if ($order->type_delivery == "delivery")
                                    <strong>Shipped To:</strong><br>
                                    {{$user->name}}<br>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->destination) }}" target="_blank">
                                        {!! nl2br(e($order->destination)) !!}
                                    </a><br>
                                    {{$order->notes}}
                                @else
                                    <strong>Pick Up By:</strong><br>
                                    {{$user->name}}<br>
                                    <a href="https://www.google.com/maps/place/Bintang+and+family/@-6.1557882,106.929422,17z/data=!3m1!4b1!4m6!3m5!1s0x2e698b00003a5d35:0x8bda2ea1bf7b6e04!8m2!3d-6.1557882!4d106.929422!16s%2Fg%2F11w207m2m7?entry=ttu" target="_blank">
                                        Lokasi Pickup
                                    </a>
                                @endif
                                  
                                  
                                </address>
                                <address>
                                    <strong>Order Date:</strong><br>
                                    {{$order->created_at}}<br><br>
                                </address>
                              </div>
                              <div class="col-md-6 text-md-right">
                                <strong>Order Status:</strong><br>
                                @if($order->order_status == 0)
                                    <div class="badge badge-danger">Menunggu Pembayaran</div>
                                @elseif($order->order_status == 1)
                                    <div class="badge badge-warning">Sedang Diproses</div>
                                @elseif($order->order_status == 2)
                                    @if ($order->type_delivery == "delivery")
                                        <div class="badge badge-secondary">Sedang Diantar</div>
                                    @else
                                        <div class="badge badge-secondary">Menunggu Dipickup</div>
                                    @endif
                                @else
                                    <div class="badge badge-success">Completed</div>
                                @endif
    
                                <br><br>
    
                                <strong>Payment Status:</strong><br>
                                @if($order->payment_status == 0)
                                    <div class="badge badge-danger">Belum Dibayar</div>
                                @elseif($order->payment_status == 1)
                                    <div class="badge badge-warning">Menunggu validasi pembayaran</div>
                                @else
                                    <div class="badge badge-success">Sudah dibayar</div>
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
        
                        <div class="row mt-4">
                          <div class="col-md-12">
                            <div class="section-title">Order Summary</div>
                            <p class="section-lead">All items here cannot be deleted.</p>
                            <div class="table-responsive">
                              <table class="table table-striped table-hover table-md">
                                <tr>
                                  <th data-width="40">#</th>
                                  <th>Item</th>
                                  <th>Additional</th>
                                  <th class="text-center"></th>
                                  <th class="text-center">QTY</th>
                                  <th class="text-right">Total Price</th>
                                </tr>
                                @foreach($orderItems as $index =>  $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div>
                                                {{ $item->product->category->name }} {{ $item->product->name }}
                                            </div>
                                            <div class="text-muted small">
                                                Rp. {{ number_format($item->product->price, 2) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->additional_id && $item->additionalProduct)
                                            <div>
                                                {{ $item->additionalProduct->category->name }} {{ $item->additionalProduct->name }}
                                            </div>
                                            <div class="text-muted small">
                                                Rp. {{ number_format($item->additionalProduct->price, 2) }}
                                            </div>
                                            @else
                                                <div class="text-center">-</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->file_pdf)
                                                <div>
                                                    <a href="{{ asset($item->file_pdf) }}" target="_blank">{{ basename($item->file_pdf) }}</a>
                                                </div>
                                                <div class="text-muted small">Page: {{ $item->number_of_page }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">Rp. {{ number_format($item->price, 2) }}</td>
                                    </tr>
                                @endforeach
                              </table>
                            </div>
                            <div class="row mt-4">
                              <div class="col-lg-8">
                                <div class="section-title">Payment Method</div>
                                <p class="section-lead">The payment method that we provide is to make it easier for you to pay invoices.</p>
                                <div class="d-flex">
                                    <div class="mr-2" style="width:100px">
                                        <img src="{{ asset('assets/img/icon/dana.png') }}" alt="Dana" style="max-width: 100px; height: auto; display: block; margin-right: 10px;">
                                    </div>
                                    <div class="mr-2" style="width:70px">
                                        <img src="{{ asset('assets/img/icon/ovo.svg') }}" alt="Ovo" style="max-width: 90px; height: auto; display: block;">
                                    </div>
                                    <div class="mr-2" style="width:100px">
                                        <img src="{{ asset('assets/img/icon/bca.png') }}" alt="BCA" style="max-width: 80px; height: auto; display: block; margin-right: 10px; padding-bottom:50px;">
                                    </div>
                                </div>
                              </div>
                              <div class="col-lg-4 text-right">
                                <div class="invoice-detail-item">
                                  <div class="invoice-detail-name">Subtotal</div>
                                  <div class="invoice-detail-value">Rp. {{ number_format($subTotal, 2) }}</div>
                                </div>
                                <div class="invoice-detail-item">
                                    <div class="invoice-detail-name">Admin Fee</div>
                                    <div class="invoice-detail-value">Rp. 2,000.00</div>
                                </div>
                                @if ($order->type_delivery == "delivery")
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Shipping</div>
                                        <div class="invoice-detail-value">Rp. {{ number_format($shipping, 2) }}</div>
                                    </div>
                                @endif
                                <hr class="mt-2 mb-2">
                                <div class="invoice-detail-item">
                                  <div class="invoice-detail-name">Total</div>
                                  <div class="invoice-detail-value invoice-detail-value-lg">Rp. {{ number_format($order->total_price, 2) }}</div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="text-md-right mb-3">
                        <div class="float-lg-left mb-lg-0 mb-3">
                            @if($order->payment_prove)
                                
                                    <a class="btn btn-primary btn-icon icon-left mb-3" href="{{ asset('storage/' . $order->payment_prove) }}" target="_blank"><i class="fas fa-credit-card"></i>View Bukti Bayar</a>
                                
                            @endif
                          
                        </div>
                      </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12">
                        @if($order->payment_prove && $order->payment_status == 1)
                            <form action="{{ route('admin.orders.pembayaran-diterima', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Pembayaran diterima
                                    </button>
                                </div>
                            </form>
                        @elseif($order->payment_prove && $order->payment_status == 2 && $order->order_status == 1)
                            @if($order->type_delivery == "delivery")
                                <form action="{{ route('admin.orders.pesanan-jadi', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Pesanan Sudah Jadi dan Sedang Diantar
                                        </button>
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('admin.orders.pesanan-jadi', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            Pesanan Sudah Jadi dan Menunggu Di PickUp
                                        </button>
                                    </div>
                                </form>
                            @endif
                            
                        @elseif($order->payment_prove && $order->payment_status == 2 && $order->order_status == 2)
                            <form action="{{ route('admin.orders.pesanan-selesai', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Pesanan Selesai
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


