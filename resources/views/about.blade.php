@extends('layouts.app')
@include('filament.components.header')

<head>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
@section('content')
    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-96 flex items-center justify-center text-white"
             style="background-image: url('{{ asset('storage/images/bg1.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 text-center">
            <h1 class="text-5xl font-extrabold">About Us</h1>
            <p class="mt-4 text-xl">Learn more about our company, mission, and values</p>
        </div>
    </section>

    <!-- Company Overview -->
    <section class="container mx-auto py-20 px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800">Who We Are</h2>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">We are a passionate and dynamic team committed to providing high-quality products and excellent customer service. Our mission is to bring the best of fashion, technology, and innovation to our customers.</p>
        </div>
        <div class="flex flex-col lg:flex-row justify-between items-center space-y-12 lg:space-y-0 lg:space-x-8">
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <h3 class="text-xl font-bold text-gray-800">Our Story</h3>
                <p class="mt-4 text-lg text-gray-600">Founded in 2020, we started as a small online store with a big dream to change the way people shop. We’ve grown into a trusted eCommerce brand with customers from all over the world, delivering top-quality products with great customer satisfaction.</p>
            </div>
            <div class="w-full lg:w-1/2">
                <img src="{{ asset('storage/images/bg2.jpeg') }}" alt="Our Story" class="rounded-lg shadow-lg w-full h-full object-cover">
            </div>
        </div>
    </section>

    <!-- Mission and Values -->
    <section class="bg-gray-50 py-20">
        <div class="container mx-auto text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800">Our Mission & Values</h2>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">Our mission is to deliver the highest quality products with an unwavering commitment to our values: integrity, quality, and customer satisfaction.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12 container mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h4 class="text-xl font-bold text-red-600">Integrity</h4>
                <p class="mt-4 text-gray-600">We value honesty, transparency, and accountability in all aspects of our business.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h4 class="text-xl font-bold text-red-600">Quality</h4>
                <p class="mt-4 text-gray-600">We are committed to providing top-notch products that are built to last and exceed expectations.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <h4 class="text-xl font-bold text-red-600">Customer Satisfaction</h4>
                <p class="mt-4 text-gray-600">Our customers are at the heart of everything we do, and we strive to provide exceptional service at all times.</p>
            </div>
        </div>
    </section>

    <!-- Meet the Team -->
    <section class="container mx-auto py-20 px-6">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800">Meet Our Team</h2>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">Our team is the driving force behind our success. We’re a group of passionate individuals who share a love for creativity, innovation, and excellence.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12">
            @foreach ([['name' => 'Wan Muhammad Izzuddin Bin Wan Dolah', 'role' => 'CEO', 'image' => 'team2.png'],
                       ['name' => 'Muhammad Syafiq Bin Mohd Fazli', 'role' => 'Marketing Lead', 'image' => 'team1.png'],
                       ['name' => 'Nur Farah Alysha Binti Abdul Rahman', 'role' => 'Product Manager', 'image' => 'team3.png']] as $member)
                <div class="text-center">
                    <img src="{{ asset('storage/images/' . $member['image']) }}" alt="{{ $member['name'] }}" class="w-40 h-40 mx-auto rounded-full shadow-lg mb-4">
                    <h4 class="text-xl font-bold text-gray-800">{{ $member['name'] }}</h4>
                    <p class="text-gray-600">{{ $member['role'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer Call to Action -->
    <section class="bg-red-600 text-white py-16 px-4 my-16 text-center" style="background-image: url('storage/images/banner.jpg');">
        <h2 class="text-3xl font-bold">Join Our Community</h2>
        <p class="mt-4 text-lg">Stay connected with us for updates on new arrivals, promotions, and more!</p>
        <a href="{{ route('contact') }}" class="mt-6 inline-block px-6 py-3 bg-white text-red-600 font-bold rounded-full shadow-lg hover:bg-transparent hover:text-white transition duration-300">
            Contact Us
        </a>
    </section>

@endsection

