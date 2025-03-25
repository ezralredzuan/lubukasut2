@extends('filament::layouts.base')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-lg max-w-4xl mx-auto">
        <div class="border-b pb-4 mb-4 text-center">
            <h2 class="text-2xl font-bold">Event Preview</h2>
        </div>

        {{-- ✅ Display the saved GrapesJS content --}}
        <div id="event-content">
            {!! $content !!}
        </div>

        {{-- ✅ Close Button --}}
        <div class="mt-6 text-center">
            <a href="{{ route('filament.admin.resources.events.index') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-800">
                Close
            </a>
        </div>
    </div>
@endsection
