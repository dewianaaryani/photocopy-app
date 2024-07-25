@extends('layouts.app')

@php
$currentRoute = \Request::route()->getName();
$pageName = '';

if ($currentRoute == 'photocopy.add') {
    $pageName = 'Photocopy';
} elseif ($currentRoute == 'printout.add') {
    $pageName = 'Printout';
} elseif ($currentRoute == 'cetakfoto.add') {
    $pageName = 'Cetak Foto';
}
@endphp

@section('title')
    {{$pageName}}
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{$pageName}}</h1>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Create New Order for {{$pageName}}</h4></div>
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
            <form method="POST" action="
                @if($currentRoute == 'photocopy.add')
                    {{ route('photocopyAddToCart') }}
                @elseif($currentRoute == 'printout.add')
                    {{ route('printoutAddToCart') }}
                @elseif($currentRoute == 'cetakfoto.add')
                    {{ route('cetakfotoAddToCart') }}
                @endif
            " enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <div class="section-title">File Browser *</div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="file_pdf" required
                                    @if($currentRoute == 'photocopy.add' || $currentRoute == 'printout.add')
                                        accept=".pdf"
                                    @elseif($currentRoute == 'cetakfoto.add')
                                        accept="image/*"
                                    @endif
                                >
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
            
                            @php
                                $fileTypeMessage = '';
            
                                if ($currentRoute == 'photocopy.add') {
                                    $fileTypeMessage = 'File harus format pdf pada fotokopi.';
                                } elseif ($currentRoute == 'printout.add') {
                                    $fileTypeMessage = 'File harus format pdf pada print out.';
                                } elseif ($currentRoute == 'cetakfoto.add') {
                                    $fileTypeMessage = 'File harus format gambar(e.g., JPG, PNG) untuk Cetak Foto.';
                                }
                            @endphp
            
                            <!-- Display the file type requirement message -->
                            <small class="form-text text-muted">
                                {{ $fileTypeMessage }}
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

                

                <!-- Quantity Section -->
                

                @if($currentRoute != 'cetakfoto.add')
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label">Select Product Color *</label>
                            <div class="selectgroup selectgroup-pills">
                                @foreach($colors as $color)
                                    <label class="selectgroup-item">
                                        <input type="radio" name="product-color" value="{{ $color->color_type }}" class="selectgroup-input" {{ $loop->first ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ $color->color_type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <!-- Checkbox for Laminating -->
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Choose one:</label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="empty-radio" name="choose-option" class="custom-control-input" value="">
                                    <label class="custom-control-label" for="empty-radio">Tidak Ada</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="laminating-radio" name="choose-option" class="custom-control-input" value="laminating">
                                    <label class="custom-control-label" for="laminating-radio">Laminating</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="jilid-radio" name="choose-option" class="custom-control-input" value="jilid">
                                    <label class="custom-control-label" for="jilid-radio">Jilid</label>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Laminating Section -->
                    <div class="row" id="laminating-section" style="display: none;">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Select Laminating Type</label>
                                <div class="selectgroup selectgroup-pills">
                                    @foreach($additionalLaminating as $laminating)
                                        <label class="selectgroup-item">
                                            <input type="radio" name="laminating-type" value="{{ $laminating->id }}" class="selectgroup-input">
                                            <span class="selectgroup-button">{{ $laminating->size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- Jilid Section -->
                    <div class="row" id="jilid-section" style="display: none;">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Select Jilid Type</label>
                                <div class="selectgroup selectgroup-pills">
                                    @foreach($jilids as $jilid)
                                        <label class="selectgroup-item">
                                            <input type="radio" name="jilid-type" value="{{ $jilid->id }}" class="selectgroup-input">
                                            <span class="selectgroup-button">{{ $jilid->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif 

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
    @if($currentRoute != 'cetakfoto.add')
    <script>
        var laminatingRadio = document.getElementById('laminating-radio');
        var emptyRadio = document.getElementById('empty-radio');
        var jilidRadio = document.getElementById('jilid-radio');

        var laminatingSection = document.getElementById('laminating-section');
        var jilidSection = document.getElementById('jilid-section');

        laminatingRadio.addEventListener('change', function() {
            if (this.checked) {
                laminatingSection.style.display = 'block';
                jilidSection.style.display = 'none';
            }
        });

        emptyRadio.addEventListener('change', function() {
            if (this.checked) {
                laminatingSection.style.display = 'none';
                jilidSection.style.display = 'none';
            }
        });

        jilidRadio.addEventListener('change', function() {
            if (this.checked) {
                laminatingSection.style.display = 'none';
                jilidSection.style.display = 'block';
            }
        });
    </script>
    @endif
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
