<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/test', function () {
    return view('test3');
})->name('test');




Route::get('home',function(){})->name('home');

Route::get('about',function(){})->name('about');
Route::get('institutions.index',function(){})->name('institutions.index');

Route::get('events.index',function(){})->name('events.index');

Route::get('funding.index',function(){})->name('funding.index');

Route::post('newsletter.subscribe',function(){})->name('newsletter.subscribe');


Route::post('privacy',function(){})->name('privacy');
Route::post('terms',function(){})->name('terms');

Route::post('cookies',function(){})->name('cookies');
Route::post('support',function(){})->name('support');

Route::post('events.show/{id}',function(){})->name('events.show');





// @foreach($slides as $index => $slide)
// <div class="slide-image {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
//     <img src="{{ $slide->image }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
// </div>
// @endforeach