<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::view('busca', 'busca')
    ->middleware(['auth', 'verified'])
    ->name('busca');

    
    Route::view('contos', 'contos')
    ->middleware(['auth', 'verified'])
    ->name('contos');


    Route::view('feed_imagens', 'feed_imagens')
    ->middleware(['auth', 'verified'])
    ->name('feed_imagens');

    Route::view('feed_videos', 'feed_videos')
    ->middleware(['auth', 'verified'])
    ->name('feed_videos');


    Route::view('programacao', 'programacao')
    ->middleware(['auth', 'verified'])
    ->name('programacao');


    Route::view('radar', 'radar')
    ->middleware(['auth', 'verified'])
    ->name('radar');


    Route::view('grupos', 'grupos')
    ->middleware(['auth', 'verified'])
    ->name('grupos');


    Route::view('bate_papo', 'bate_papo')
    ->middleware(['auth', 'verified'])
    ->name('bate_papo');


    Route::view('caixa_de_mensagens', 'caixa_de_mensagens')
    ->middleware(['auth', 'verified'])
    ->name('caixa_de_mensagens');




Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
