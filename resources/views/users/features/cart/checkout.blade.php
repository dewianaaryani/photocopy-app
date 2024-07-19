@extends('layouts.app')

@section('title')
    Checkout
@endsection

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h4>Checkout</h4>
        </div>
        <form action="{{ route('cart.checkout') }}" method="post" enctype="multipart/form-data">
            @csrf
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
                
                <div class="section-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="invoice-title">
                                        <h2>Order</h2>
                                        <hr>
                                        <div class="form-group">
                                            <label for="options">Choose an option:</label>
                                            <div>
                                                <input type="radio" id="pickup" name="deliveryOption" value="pickup" onclick="toggleOption()">
                                                <label for="pickup">Pickup</label>
                                            </div>
                                            <div>
                                                <input type="radio" id="delivery" name="deliveryOption" value="delivery" onclick="toggleOption()">
                                                <label for="delivery">Delivery</label>
                                            </div>
                                        </div>

                                        <div id="pickup-message" class="alert alert-primary" style="display:none;">
                                            Kamu harus mempick up di <a href="https://www.google.com/maps/search/?api=1&query=-6.6487692,106.8374509" target="_blank">lokasi ini</a>.
                                        </div>

                                        <div id="delivery-input" style="display:none;">
                                            <input id="autocomplete" type="text" class="form-control" placeholder="Enter destination" name="destination">
                                            <div id="distance" style="margin-top: 20px; font-size: 18px;" class="mb-1"></div>
                                            <div id="delivery-notes" style="display:none;">
                                                <label for="delivery-notes">Delivery Notes:</label>
                                                <textarea id="delivery-notes" class="form-control" rows="3" placeholder="Enter delivery notes" name="notes"></textarea>
                                            </div>
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
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th></th>
                                                    <th class="text-center"></th>
                                                    <th class="text-center">QTY</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($userCartItems as $item)
                                                <tr>
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
                                                        -
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->file_pdf)
                                                            <div>
                                                                <a href="{{ asset($item->file_pdf) }}" target="_blank">{{ basename($item->file_pdf) }}</a>
                                                            </div>
                                                            <div class="text-muted small">
                                                                Page : {{ $item->number_of_page }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                    <td>Rp. {{ number_format($item->price, 2) }}</td>
                                                    <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][additional_id]" value="{{ $item->additional_id }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][file_pdf]" value="{{ $item->file_pdf }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][number_of_page]" value="{{ $item->number_of_page }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][quantity]" value="{{ $item->quantity }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][price]" value="{{ $item->price }}">
                                                    <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                                </tr>
                                                @endforeach
                                            </tbody>
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
                                                <div class="invoice-detail-value" id="subtotal">Rp. 0</div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Shipping</div>
                                                <div class="invoice-detail-value" id="shipping-cost">Rp. 0</div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Admin Fee</div>
                                                <div class="invoice-detail-value">Rp. 2,000</div>
                                            </div>
                                            <hr class="mt-2 mb-2">
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Total</div>
                                                <div class="invoice-detail-value invoice-detail-value-lg" id="total">Rp. 0</div>
                                                <input type="hidden" name="total_price" id="total_price" value="">
                                                <input type="hidden" name="distance_input" id="distance_input" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-md-right">
                                <div class="float-lg-left mb-lg-0 mb-3">
                                    <button type="submit" class="btn btn-primary btn-icon icon-left"><i class="fas fa-shopping-cart"></i>Checkout</button>
                                    <button type="button" class="btn btn-danger btn-icon icon-left" onclick="history.back()"><i class="fas fa-times"></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&libraries=places"></script>
<script>
    let origin = { lat: {{ $origin['lat'] }}, lng: {{ $origin['lng'] }} };
    let distanceInKm = 0;
    const ratePerKm = 7000; // Adjust the rate per kilometer as needed

    function toggleOption() {
        const pickupMessage = document.getElementById('pickup-message');
        const deliveryInput = document.getElementById('delivery-input');
        const deliveryNotes = document.getElementById('delivery-notes');
        const destinationInput = document.getElementById('autocomplete');
        const notesTextarea = document.querySelector('textarea[name="notes"]');

        if (document.getElementById('pickup').checked) {
            pickupMessage.style.display = 'block';
            deliveryInput.style.display = 'none';
            deliveryNotes.style.display = 'none';
            destinationInput.value = ''; // Clear the destination input field
            notesTextarea.value = ''; // Clear the notes textarea
            calculateShipping(0); // No shipping cost for pickup
        } else if (document.getElementById('delivery').checked) {
            pickupMessage.style.display = 'none';
            deliveryInput.style.display = 'block';
            deliveryNotes.style.display = 'block';
        }
    }

    function initAutocomplete() {
        const input = document.getElementById('autocomplete');
        const autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                console.log("Autocomplete's returned place contains no geometry");
                return;
            }

            const destination = place.geometry.location;
            calculateDistance(origin, destination);
        });
    }

    function calculateDistance(origin, destination) {
        const service = new google.maps.DistanceMatrixService();
        
        service.getDistanceMatrix(
            {
                origins: [origin],
                destinations: [destination],
                travelMode: 'DRIVING',
            },
            function(response, status) {
                if (status !== 'OK') {
                    alert('Error was: ' + status);
                } else {
                    const results = response.rows[0].elements;

                    const distanceValue = results[0].distance.value; // Distance in meters
                    distanceInKm = distanceValue / 1000; // Convert to kilometers
                    distanceInKm = Math.ceil(distanceInKm * 2) / 2; // Round up to nearest 0.5 kilometers

                    const distanceText = results[0].distance.text;
                    const durationText = results[0].duration.text;

                    document.getElementById('distance').innerText = `Distance: ${distanceText}`;
                    document.getElementById('distance_input').value = distanceInKm.toFixed(2);
                    
                    calculateShipping(distanceInKm);
                }
            }
        );
    }

    function calculateShipping(distance) {
        const shippingCost = distance * ratePerKm;
        console.log('Shipping Cost:', shippingCost);
        document.getElementById('shipping-cost').innerText = `Rp. ${numberWithCommas(shippingCost.toFixed(2))}`;
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        const items = document.querySelectorAll('tbody tr');

        items.forEach(item => {
            const mainPriceCell = item.querySelector('td:nth-child(5)'); // Main product price cell

            if (mainPriceCell) {
                const mainPrice = parseFloat(mainPriceCell.innerText.replace('Rp. ', '').replace(',', ''));
                subtotal += mainPrice;
            }
        });

        const adminFee = 2000;
        const shippingCost = document.getElementById('delivery').checked ? parseFloat(document.getElementById('shipping-cost').innerText.replace('Rp. ', '').replace(',', '')) : 0;
        const total = subtotal + adminFee + shippingCost;

        document.getElementById('subtotal').innerText = `Rp. ${numberWithCommas(subtotal.toFixed(2))}`;
        document.getElementById('total').innerText = `Rp. ${numberWithCommas(total.toFixed(2))}`;
        document.getElementById('total_price').value = total.toFixed(2);
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleOption(); // Initial toggle based on checked radio button
        initAutocomplete(); // Initialize Google Places Autocomplete
    });
</script>
@endsection
