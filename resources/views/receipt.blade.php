@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card p-4 text-center shadow">
        <h2>Thank you for buying with us!</h2>
        <p class="text-muted mb-4">Here is your order receipt:</p>

        <div class="text-left">
            <p><strong>Full Name:</strong> {{ $fullName }}</p>
            <p><strong>Reference Number:</strong> {{ $order->reference_no }}</p>
            <p><strong>Order Date:</strong> {{ $order->order_date->format('d M Y, h:i A') }}</p>
        </div>

        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Return to Home</a>
    </div>
</div>
@endsection
