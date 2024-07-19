@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h4>Products</h4>
            <div class="card-header-form">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search" value="{{ request('search') }}">
                        <div class="input-group-btn">
                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Color Type</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->size }}</td>
                            <td>{{ $product->color_type }}</td>
                            <td>{{ $product->price }}</td>
                            <td>
                                {{-- Popup for Quantity Selection --}}
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#quantityModal{{ $product->id }}">
                                    Add to Cart
                                </button>

                                <!-- Quantity Selection Modal -->
                                <div class="modal fade" id="quantityModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('productAddToCart', $product->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="quantityModalLabel{{ $product->id }}">Select Quantity</h5>
                                                   
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="quantity">Quantity:</label>
                                                        <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    
                                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
