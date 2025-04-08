@extends('layouts.app')

@include('filament.components.header')

<script src="https://cdn.tailwindcss.com"></script>

@php
$cart = session('cart', []);
$total = array_sum(array_column($cart, 'price'));
$customer = session('customer');
@endphp

@section('content')
<div class="container mt-5">
    <div class="row">
        <!-- Left Side: Order & Payment Information (3/4 width) -->
        <div class="col-md-9">
            <div class="card p-4 mb-4">
                @if(Session::has('customer'))
                    <p>Thank you for your purchase, {{ Session::get('customer')->full_name }}!</p>
                @else
                    <p>No customer session found. Please log in.</p>
                @endif

                <h4>Order & Payment Information</h4>

                <!-- Full Name Input Field -->
                <div class="form-group mb-3" id="full-name-group" style="{{ Session::has('customer') ? 'display:none;' : '' }}">
                    <label for="full_name">Full Name</label>
                    <input type="text" class="form-control" name="full_name" placeholder="Enter your full name" required>
                </div>

                <form action="{{ route('checkout.store') }}" method="POST" id="payment-form">
                    @csrf

                    <!-- Customer Information -->
                    <div class="form-group mb-3">
                        <label for="no_phone">Phone Number</label>
                        <input type="text" class="form-control" name="no_phone" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="city">City</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="state">State</label>
                        <input type="text" class="form-control" name="state" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" class="form-control" name="postal_code" required>
                    </div>

                    <!-- Payment Section -->
                    <h5 class="mt-4">Payment Information</h5>
                    <input type="hidden" name="total_amount" x-model="cartTotal">
                    <div class="form-group mb-3">
                        <label for="card-element">Credit or Debit Card</label>
                        <div id="card-element" class="form-control"></div>
                        <div id="card-errors" class="text-danger mt-2"></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block mt-3">Complete Order</button>
                </form>
            </div>
        </div>

        <!-- Right Side: Your Order Summary (1/4 width) -->
        <div class="col-md-3">
            <div class="card p-4">
                <h4>Your Order Summary</h4>

                <!-- Coupon Input -->
                <div class="mb-3">
                    <label for="coupon_code">Promo Code</label>
                    <div class="input-group">
                        <input type="text" id="coupon_code" class="form-control" placeholder="Enter promo code">
                        <button class="btn btn-primary" onclick="applyCoupon()">Apply</button>
                    </div>
                    <small id="coupon_message" class="text-danger"></small>
                </div>

                <ul class="list-group">
                    @foreach ($cart as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['name'] }}</strong> (RM {{ number_format($item['price'], 2) }})
                            </div>
                            <span>RM {{ number_format($item['price'], 2) }}</span>
                        </li>
                    @endforeach
                </ul>

                <hr>

                <!-- Discount Section -->
                <h5>Discount: <span id="discount_amount">RM 0.00</span></h5>

                <!-- Total Price -->
                <h5>Total: <strong id="total_price">RM {{ number_format($total, 2) }}</strong></h5>
                <input type="hidden" name="cart_items" value='@json($cart)'>
                <input type="hidden" name="total_price" value="{{ $total }}">

            </div>
        </div>

    </div>
</div>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe("{{ env('STRIPE_KEY') }}");
    var elements = stripe.elements();
    var card = elements.create("card");
    card.mount("#card-element");

    var form = document.getElementById("payment-form");
    form.addEventListener("submit", function(event) {
        event.preventDefault();
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                document.getElementById("card-errors").textContent = result.error.message;
            } else {
                var hiddenInput = document.createElement("input");
                hiddenInput.setAttribute("type", "hidden");
                hiddenInput.setAttribute("name", "stripeToken");
                hiddenInput.setAttribute("value", result.token.id);
                form.appendChild(hiddenInput);
                form.submit();
            }
        });
    });

    function applyCoupon() {
        let couponCode = document.getElementById('coupon_code').value;
        let totalPrice = parseFloat({{ $total }});

        fetch("{{ url('/checkout/apply-coupon') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ coupon_code: couponCode, total_price: totalPrice })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('discount_amount').textContent = `RM ${data.discount}`;
                document.getElementById('total_price').textContent = `RM ${data.new_total}`;
                document.getElementById('coupon_message').textContent = "Coupon applied successfully!";
                document.getElementById('coupon_message').classList.remove('text-danger');
                document.getElementById('coupon_message').classList.add('text-success');
            } else {
                document.getElementById('coupon_message').textContent = data.message;
                document.getElementById('coupon_message').classList.remove('text-success');
                document.getElementById('coupon_message').classList.add('text-danger');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const productIds = cart.map(item => item.id);

        // Insert into hidden input
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'product_ids[]';

        productIds.forEach(id => {
            let clone = input.cloneNode();
            clone.value = id;
            document.getElementById('payment-form').appendChild(clone);
        });
    });
</script>
@endsection
