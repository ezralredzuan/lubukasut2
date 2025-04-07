@php
    $customer = session('customer');
@endphp

@extends('layouts.app')
@include('filament.components.header')

 <!-- #region -->
 <script src="https://cdn.tailwindcss.com"></script>

@section('content')

<div class="container mx-auto px-6 py-12">
    <!-- FAQ Section -->
    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Frequently Asked Questions</h2>
    <div class="max-w-3xl mx-auto">
        <div x-data="{ open: null }" class="space-y-4">
            @foreach ([
                'How do I place an order?' => 'You can place an order by browsing our products and adding them to the cart.',
                'What payment methods do you accept?' => 'We accept credit/debit cards, PayPal, and bank transfers.',
                'How can I track my order?' => 'After placing an order, you will receive an email with tracking details.',
                'What is your return policy?' => 'You can return any unused item within 30 days for a full refund.',
            ] as $question => $answer)
            <div class="border rounded-md shadow-md">
                <button @click="open === {{ $loop->index }} ? open = null : open = {{ $loop->index }}" class="w-full text-left p-4 bg-gray-200 hover:bg-gray-300 focus:outline-none">
                    <span class="font-semibold">{{ $question }}</span>
                </button>
                <div x-show="open === {{ $loop->index }}" class="p-4 bg-white border-t">
                    <p class="text-gray-700">{{ $answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Inquiries Form -->
    <h2 class="text-3xl font-bold text-gray-800 mt-12 mb-6 text-center">Have an Inquiry?</h2>
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <form action="{{ route('inquiries.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full p-3 border rounded-md focus:ring focus:ring-yellow-400">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Inquiries Title</label>
                <input type="text" name="inquiries_title" required class="w-full p-3 border rounded-md focus:ring focus:ring-yellow-400">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea name="description" rows="4" required class="w-full p-3 border rounded-md focus:ring focus:ring-yellow-400"></textarea>
            </div>
            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-md">Submit Inquiry</button>
        </form>
    </div>
</div>
@endsection
