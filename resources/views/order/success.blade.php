@extends('layouts.app')
@include('filament.components.header')
<script src="https://cdn.tailwindcss.com"></script>

@section('content')
<div class="container mt-5">
    <div class="card p-4 text-center shadow">
        <h2 class="mb-3">Thank you for buying with us!</h2>
        <p class="text-muted mb-4">Here is your order receipt:</p>

        @php
            // Check if the user is authenticated
            $fullName = Auth::check() ? Auth::user()->name : (Session::has('customer') ? Session::get('customer')->full_name : 'Guest');
        @endphp

        @if ($order)
            <div class="text-left">
                <p><strong>Full Name:</strong> {{ $fullName }}</p>
                <p><strong>Reference Number:</strong> {{ $order->reference_no }}</p>
                <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, h:i A') }}</p>
            </div>
        @else
            <p class="text-danger">Order not found.</p>
        @endif

        <a href="{{ url('/') }}" class="btn btn-primary mt-4">Return to Home</a>
    </div>
</div>
@endsection

<script>
    // Clear the cart from local storage
    localStorage.removeItem('cart');
</script>
