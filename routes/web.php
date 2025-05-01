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
use App\Models\Post;
use App\Livewire\CreateConto;
use App\Livewire\EditConto;
use App\Livewire\Timeline;
use App\Http\Controllers\PaymentController;
use App\Livewire\Messages;
use App\Models\Message;
use App\Http\Controllers\MessageController;

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

Route::get('/contos/{id}', function($id) {
    $conto = App\Models\Conto::with(['user', 'category'])->find($id);
    return view('livewire.show-conto', compact('conto'));
})->middleware(['auth', 'verified'])->name('contos.show');


Route::get('feed_imagens', function () {
    $posts = Post::whereNotNull('image')->get();
    return view('feed_imagens', compact('posts'));
})->middleware(['auth', 'verified'])->name('feed_imagens');

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

Route::get('/meus-pagamentos', [PaymentController::class, 'index'])->name('meus-pagamentos');


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
    Route::get('/contos/create', CreateConto::class)->name('contos.create');
    Route::get('/contos/{conto}/edit', EditConto::class)->name('contos.edit')->where('conto', '[0-9]+');
    Route::delete('/contos/{conto}', function ($conto) {
        $conto = App\Models\Conto::findOrFail($conto);

        if (auth()->id() !== $conto->user_id) {
            abort(403, 'Você não tem permissão para excluir este conto.');
        }

        $conto->delete();

        return redirect()->route('contos')->with('message', 'Conto excluído com sucesso!');
    })->name('contos.destroy');
    Route::get('/timeline', Timeline::class)->name('timeline');
    Route::resource('messages', MessageController::class)->only(['index', 'store', 'destroy']);
    
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

Route::get('post/{post}', function (Post $post) {
    return view('post.show', compact('post'));
})->name('post.show');


require __DIR__.'/auth.php';
