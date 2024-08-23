@extends('layouts.app')



@section('title')
    Cetak Foto
@endsection

@section('content')
<section class="section">
    <div class="section-header d-flex justify-content-between align-items-center ">
        <h1 class="mb-0">Cetak Foto</h1>
        <nav aria-label="breadcrumb" class="md:ml-auto">
            <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    
                    <li class="breadcrumb-item active" aria-current="page">Cetak Foto</li>
                </ol>
            </nav>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Create New Order for Cetak Foto</h4></div>
        @if(session('status'))
            <div class="col-12">
                <div class="alert alert-success mt-1 mb-1">{{session('status')}}</div>
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
        <div class="card-body">
            <form method="POST" action="cetakfotoAddToCart" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="section-title">File Browser *</div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="file_pdf" required accept="image/*" >
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
            
            
                            <!-- Display the file type requirement message -->
                            <small class="form-text text-muted">
                                File harus format gambar(e.g., JPG, PNG) untuk Cetak Foto.
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Quantity *</label>
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-primary" onclick="decrementQuantity()">-</button>
                                </div>
                                <input type="text" class="form-control text-center" id="quantity-input" name="quantity" value="1">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" onclick="incrementQuantity()">+</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Quantity menunjukkan jumlah rangkap yang diinginkan. Misalkan hanya ingin membuat satu rangkap file, berarti hanya satu rangkap file yang akan dibuat.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Select Product Size *</label>
                            <div class="selectgroup selectgroup-pills">
                                @foreach($sizes as $size)
                                    <label class="selectgroup-item">
                                        <input type="radio" name="product-size" value="{{ $size->size }}" class="selectgroup-input" {{ $loop->first ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ $size->size }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                

               
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        function decrementQuantity() {
            var quantityInput = document.getElementById('quantity-input');
            var currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }

        function incrementQuantity() {
            var quantityInput = document.getElementById('quantity-input');
            var currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        }
    </script>
    
    <script>
        $(document).ready(function() {
            $('#customFile').change(function(event) {
                // Get the selected file name
                var fileName = event.target.files[0].name;
    
                // Update the label text with the file name
                $(this).next('.custom-file-label').text(fileName);
            });
        });
    </script>
@endsection
