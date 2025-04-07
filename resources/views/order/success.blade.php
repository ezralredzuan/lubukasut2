@extends('layouts.app')

@section('content')
    <div class="text-center mt-10">
        <h1 class="text-2xl font-bold text-green-500">Order Successful!</h1>
        <p>Thank you for your purchase. Your order has been processed.</p>
        <a href="{{ route('home') }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded">
            Back to Home
        </a>
    </div>
@endsection
