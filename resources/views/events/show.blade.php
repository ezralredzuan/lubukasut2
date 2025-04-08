@extends('layouts.app')
@include('filament.components.header')
<script src="https://cdn.tailwindcss.com"></script>

@section('content')
<div class="container mt-5">
    <h2>{{ $event->title }}</h2>
    <div>
        {!! $event->content !!} <!-- Render the HTML content -->
    </div>
    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Back to Events</a>
</div>
@endsection
