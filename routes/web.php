<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/event/{id}', function ($id) {
    $event = Event::findOrFail($id);
    return view('event.show', compact('event'));
})->name('event.show');
