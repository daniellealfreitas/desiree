<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\UserProfileForm;
use Livewire\Volt\Volt;
use App\Http\Livewire\PostFeed;
use App\Livewire\CreatePost;
use App\Livewire\ProfileComponent;
use App\Http\Livewire\FollowRequestsHandler;
use App\Livewire\FollowRequestNotifications;
use App\Http\Livewire\ContosForm;
use App\Http\Controllers\GroupController;
use App\Http\Livewire\NearbyUsers;
use App\Http\Controllers\LocationController;

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

    
Route::get('/contos', function () {
    return view('contos');
})->middleware(['auth', 'verified'])->name('contos');


Route::view('feed_imagens', 'feed_imagens')
    ->middleware(['auth', 'verified'])
    ->name('feed_imagens');

Route::view('feed_videos', 'feed_videos')
    ->middleware(['auth', 'verified'])
    ->name('feed_videos');


Route::view('programacao', 'programacao')
    ->middleware(['auth', 'verified'])
    ->name('programacao');


// Radar routes
Route::view('/radar', 'radar')->name('radar')->middleware('auth');

Route::view('grupos', 'grupos')
    ->middleware(['auth', 'verified'])
    ->name('grupos');


Route::view('bate_papo', 'bate_papo')
    ->middleware(['auth', 'verified'])
    ->name('bate_papo');


Route::view('caixa_de_mensagens', 'caixa_de_mensagens')
    ->middleware(['auth', 'verified'])
    ->name('caixa_de_mensagens');

Route::view('renovar-vip', 'renovar-vip')
    ->middleware(['auth', 'verified'])
    ->name('renovar-vip');

Route::view('meus-pagamentos', 'meus-pagamentos')
    ->middleware(['auth', 'verified'])
    ->name('meus-pagamentos');


Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('register', function () {
        return view('auth.register');
    })->name('register');
});


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/profile-with-avatar', 'settings.profile-with-avatar')->name('settings.profile-with-avatar');
    Volt::route('settings/profile-with-cover', 'settings.profile-with-cover')->name('settings.profile-with-cover');
    Route::get('/follow-requests', FollowRequestNotifications::class)->name('follow.requests');
});


// Rota para alternar curtidas (Livewire pode ser usado, mas aqui um POST simples)
Route::post('likes/toggle/{post}', [LikeController::class, 'toggle'])->name('likes.toggle')->middleware('auth');

// Recursos de Seguidores
Route::post('follows/toggle/{user}', [FollowController::class, 'toggle'])->name('follows.toggle')->middleware('auth');




// Rota para processar o upload da foto
Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto'])->name('user.uploadPhoto');

// Rota para exibir perfil do usuário pelo username
Route::get('/{username}', function($username) {
    return view('profile-page', ['username' => $username]);
})->name('user.profile');


// Rotas para buscar estados e cidades
Route::get('/estados', [LocationController::class, 'getStates'])->name('get.states');
Route::get('/cidades/{state}', [LocationController::class, 'getCities'])->name('get.cities');


require __DIR__.'/auth.php';
