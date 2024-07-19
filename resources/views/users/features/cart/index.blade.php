@extends('layouts.app')

@section('title')
Cart
@endsection

@section('content')
    <div class="container">
        <div class="card card-primary">
            <div class="card-header"><h4>Your Cart</h4></div>
            <div class="card-body">
                <div class="row">
                    @if(session('status'))
                        <div class="col-12">
                            <div class="alert alert-success mt-1 mb-1">{{ session('status') }}</div>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="col-12">
                            <div class="alert alert-danger mt-1 mb-1">{{ session('error') }}</div>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="col-12">
                            <div class="alert alert-danger mt-1 mb-1">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                <form action="{{ route('cart.checkoutForm') }}" method="get">
                    @csrf
                    @forelse ($cartItems as $item)
                        <div class="row">
                            <div class="col-12">
                                <div class="items">
                                    <div class="product">
                                        <div class="row">
                                            
                                            <div class="col-md-12">
                                                <div class="info">
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <div><br><input type="checkbox" name="selected_items[]" value="{{ $item->id }}"></div>
                                                        </div>
                                                        <div class="col-md-3 product-name">
                                                            <div class="text-primary font-weight-bold">
                                                                {{ ucfirst($item->product->category->name) }} {{ $item->product->name }}
                                                            </div>
                                                            <div class="text-muted">
                                                                Rp. {{ number_format($item->product->price, 2) }}
                                                            </div>
                                                            @if ($item->additional_id)
                                                                <div class="small">
                                                                    <div>additional :</div>
                                                                    <div>{{ ucfirst($item->additionalProduct->category->name) }} {{ $item->additionalProduct->name }} Rp. {{ number_format($item->additionalProduct->price, 2) }}</div>
                                                                </div>
                                                            @endif
                                                            
                                                        </div>
                                                        <div class="col-md-3 quantity">
                                                            @if ($item->file_pdf)
                                                                <a href="{{ asset($item->file_pdf) }}" target="_blank">{{ basename($item->file_pdf) }}</a>
                                                                <br>
                                                                <span>Page : {{$item->number_of_page}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-2 quantity">
                                                            
                                                            <label for="quantity">Qty : {{$item->quantity}}</label>
                                                        </div>
                                                        
                                                        <div class="col-md-2 price">
                                                            
                                                            
                                                            <span>Rp. {{ number_format($item->price, 2) }}</span>
                                                        </div>
                                                        
                                                        <div class="col-md-1 price">
                                                            <br>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-item-id="{{ $item->id }}"><i class="fas fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div> 
                    @empty
                        <li class="list-group-item">Your cart is empty</li>
                    @endforelse
                    <button type="submit" class="btn btn-primary mt-3">Checkout</button>
                </form>

                <form id="delete-form" action="" method="POST" style="display: none;">
                    @csrf
                </form>
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
    {{-- Add any additional scripts here if necessary --}}
@endsection
