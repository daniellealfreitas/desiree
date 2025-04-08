<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserLevelController;
use App\Http\Livewire\UserProfileForm;
use Livewire\Volt\Volt;
use App\Http\Livewire\PostFeed;
use App\Livewire\CreatePost;

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

// Recursos de Postagens
Route::resource('posts', PostController::class)->middleware('auth');

// Rota para alternar curtidas (Livewire pode ser usado, mas aqui um POST simples)
Route::post('likes/toggle/{post}', [LikeController::class, 'toggle'])->name('likes.toggle')->middleware('auth');

// Recursos de Seguidores
Route::post('follows/toggle/{user}', [FollowController::class, 'toggle'])->name('follows.toggle')->middleware('auth');

// Rotas para editar perfil (Livewire)
// Route::get('profile/edit', UserProfileForm::class)->name('user.profile.edit')->middleware('auth');

// Rotas para níveis (apenas leitura)
Route::get('levels', [UserLevelController::class, 'index'])->name('levels.index')->middleware('auth');

require __DIR__.'/auth.php';
