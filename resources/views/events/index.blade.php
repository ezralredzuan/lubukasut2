@extends('layouts.app')
@include('filament.components.header')
<script src="https://cdn.tailwindcss.com"></script>

@section('content')
<div class="container mt-5">

    <!-- Full Width Carousel Banner -->
    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($events as $index => $event)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $event->image_cover) }}" class="d-block w-100 h-64 object-cover" alt="{{ $event->title }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="text-4xl font-bold text-white bg-opacity-50 bg-black rounded">{{ $event->title }}</h5>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <h2 class="text-3xl font-bold  mb-5 text-center mt-5">Events</h2> <!-- Title for the Events -->

    <div class="grid grid-cols-1 my-5 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden transform transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('storage/' . $event->image_cover) }}" class="w-full h-48 object-cover" alt="{{ $event->title }}">
                <div class="p-4">
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">{{ $event->title }}</h5>
                    <p class="text-gray-600 mb-4">{{ Str::limit($event->content, 100) }}</p> <!-- Display a preview of content -->
                    <a href="{{ route('events.show', $event->event_id) }}" class="mt-2 inline-block px-6 py-2 bg-red-600 text-white font-bold rounded-full shadow hover:bg-red-700 transition duration-300 w-full text-center">View Details</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Optional: Manually initialize the carousel -->
<script>
    var myCarousel = document.querySelector('#eventCarousel')
    var carousel = new bootstrap.Carousel(myCarousel, {
        interval: 2000, // Slide interval in milliseconds
        wrap: true // Enable wrapping around the slides
    });
</script>

@endsection
