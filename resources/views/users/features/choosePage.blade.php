@extends('layouts.app')

@php
$currentRoute = \Request::route()->getName();
$pageName = '';

if ($currentRoute == 'photocopyChoose') {
    $pageName = 'Photocopy';
} elseif ($currentRoute == 'printoutChoose') {
    $pageName = 'Printout';
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
                @if($currentRoute == 'photocopyChoose')
                    {{ route('photocopyChoosed', $cart->id) }}
                @elseif($currentRoute == 'printoutChoose')
                    {{ route('printoutChoosed', $cart->id) }}
                @endif
            " enctype="multipart/form-data">
                @csrf

                <label for=""><strong>File : </strong><a href="{{ asset($cart->file_pdf) }}" target="_blank">{{ basename($cart->file_pdf) }}</a></label>
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text"  value="{{$pageName}}  {{ $cart->product ? $cart->product->name : 'N/A' }}" class="form-control" readonly>
                </div>
                
                
                <div class="form-group">
                    <label for="name">Total Halaman</label>
                    <input type="text"  value="{{$cart->number_of_page}}" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="pages">Pilih Halaman</label>
                    <div class="form-group">
                        <label class="selectgroup-item">
                            <input type="checkbox" id="select-all" class="selectgroup-input">
                            <span class="selectgroup-button">Pilih Semua</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="checkbox" id="select-odd" class="selectgroup-input">
                            <span class="selectgroup-button">Pilih Ganjil</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="checkbox" id="select-even" class="selectgroup-input">
                            <span class="selectgroup-button">Pilih Genap</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="selectgroup selectgroup-pills" id="page-selection">
                            @for ($i = 1; $i <= $cart->number_of_page; $i++)
                                <label class="selectgroup-item">
                                    <input type="checkbox" name="selected_pages[]" value="{{ $i }}" class="selectgroup-input page-checkbox">
                                    <span class="selectgroup-button">{{ $i }}</span>
                                </label>
                            @endfor
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
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Submit
                    </button>
                   
                </div>
            </form>
           
            <form method="POST" action=" @if($currentRoute == 'photocopyChoose')
                    {{ route('photocopyCancel', $cart->id) }}
                @elseif($currentRoute == 'printoutChoose')
                    {{ route('printoutCancel', $cart->id) }}
                @endif" style="display:inline;">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-danger btn-lg btn-block" onclick="return confirm('Are you sure you want to delete this record?')">
                    Cancel
                </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        var selectAllCheckbox = document.getElementById('select-all');
        var selectOddCheckbox = document.getElementById('select-odd');
        var selectEvenCheckbox = document.getElementById('select-even');
        var pageCheckboxes = document.querySelectorAll('.page-checkbox');

        function clearOtherSelections(selectedCheckbox) {
            if (selectedCheckbox !== selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            if (selectedCheckbox !== selectOddCheckbox) {
                selectOddCheckbox.checked = false;
            }
            if (selectedCheckbox !== selectEvenCheckbox) {
                selectEvenCheckbox.checked = false;
            }
        }

        selectAllCheckbox.addEventListener('change', function() {
            clearOtherSelections(selectAllCheckbox);
            if (selectAllCheckbox.checked) {
                pageCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = true;
                });
            } else {
                pageCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });

        selectOddCheckbox.addEventListener('change', function() {
            clearOtherSelections(selectOddCheckbox);
            if (selectOddCheckbox.checked) {
                pageCheckboxes.forEach(function(checkbox) {
                    var pageNumber = parseInt(checkbox.value);
                    checkbox.checked = pageNumber % 2 !== 0;
                });
            } else {
                pageCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });

        selectEvenCheckbox.addEventListener('change', function() {
            clearOtherSelections(selectEvenCheckbox);
            if (selectEvenCheckbox.checked) {
                pageCheckboxes.forEach(function(checkbox) {
                    var pageNumber = parseInt(checkbox.value);
                    checkbox.checked = pageNumber % 2 === 0;
                });
            } else {
                pageCheckboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
            }
        });

        // Ensure that individual checkboxes respect the odd/even selection
        pageCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (!this.checked) {
                    selectOddCheckbox.checked = false;
                    selectEvenCheckbox.checked = false;
                    selectAllCheckbox.checked = Array.from(pageCheckboxes).every(cb => cb.checked);
                } else {
                    selectAllCheckbox.checked = Array.from(pageCheckboxes).every(cb => cb.checked);
                }
            });
        });
    });
</script>


    
   
@endsection
