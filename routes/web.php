<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
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
use App\Models\Post;
use App\Livewire\CreateConto;
use App\Livewire\EditConto;
use App\Livewire\Timeline;
use App\Http\Controllers\PaymentController;
use App\Livewire\Messages;
use App\Livewire\UserPointsHistory;
use App\Models\Message;
use App\Http\Controllers\MessageController;
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


// Rotas de eventos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/eventos', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/eventos/{slug}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    Route::post('/eventos/{event}/registrar', [App\Http\Controllers\EventAttendeeController::class, 'register'])->name('events.register');
    Route::get('/eventos/{event}/pagamento/sucesso', [App\Http\Controllers\EventAttendeeController::class, 'paymentSuccess'])->name('events.payment.success');
    Route::get('/eventos/{event}/pagamento/cancelar', [App\Http\Controllers\EventAttendeeController::class, 'paymentCancel'])->name('events.payment.cancel');
    Route::post('/eventos/{event}/cancelar', [App\Http\Controllers\EventAttendeeController::class, 'cancel'])->name('events.cancel');
});

// Rotas de administração de eventos
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/eventos/criar', [App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/eventos', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/eventos/{event}/editar', [App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
    Route::put('/eventos/{event}', [App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/eventos/{event}', [App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/eventos/{event}/participantes/{attendee}/check-in', [App\Http\Controllers\EventAttendeeController::class, 'checkIn'])->name('events.attendee.check-in');
});

Route::view('programacao', 'programacao')
    ->middleware(['auth', 'verified'])
    ->name('programacao');


// Radar routes
Route::view('/radar', 'radar')->name('radar')->middleware('auth');
Route::post('/update-user-location', [LocationController::class, 'updateLocation'])->middleware('auth');

// Rotas de grupos
Route::prefix('grupos')->name('grupos.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\GroupController::class, 'index'])->name('index');
    Route::get('/criar', function () {
        return view('groups.create');
    })->name('create');
    Route::post('/', [App\Http\Controllers\GroupController::class, 'store'])->name('store');
    Route::get('/{group:slug}', [App\Http\Controllers\GroupController::class, 'show'])->name('show');
    Route::get('/{group:slug}/editar', [App\Http\Controllers\GroupController::class, 'edit'])->name('edit');
    Route::put('/{group}', [App\Http\Controllers\GroupController::class, 'update'])->name('update');
    Route::delete('/{group}', [App\Http\Controllers\GroupController::class, 'destroy'])->name('destroy');

    // Rotas de membros
    Route::get('/{group:slug}/membros', [App\Http\Controllers\GroupMemberController::class, 'index'])->name('members.index');
    Route::post('/{group}/membros/{user}/aprovar', [App\Http\Controllers\GroupMemberController::class, 'approve'])->name('members.approve');
    Route::post('/{group}/membros/{user}/rejeitar', [App\Http\Controllers\GroupMemberController::class, 'reject'])->name('members.reject');
    Route::post('/{group}/membros/{user}/remover', [App\Http\Controllers\GroupMemberController::class, 'remove'])->name('members.remove');
    Route::post('/{group}/membros/{user}/alterar-funcao', [App\Http\Controllers\GroupMemberController::class, 'changeRole'])->name('members.change-role');

    // Rotas de convites
    Route::get('/convites', [App\Http\Controllers\GroupInvitationController::class, 'index'])->name('invitations.index');
    Route::post('/{group}/convidar', [App\Http\Controllers\GroupInvitationController::class, 'store'])->name('invitations.store');
    Route::put('/convites/{invitation}/aceitar', [App\Http\Controllers\GroupInvitationController::class, 'accept'])->name('invitations.accept');
    Route::put('/convites/{invitation}/recusar', [App\Http\Controllers\GroupInvitationController::class, 'decline'])->name('invitations.decline');
    Route::delete('/convites/{invitation}/cancelar', [App\Http\Controllers\GroupInvitationController::class, 'cancel'])->name('invitations.cancel');

    // Rotas de ações
    Route::post('/{group}/entrar', [App\Http\Controllers\GroupController::class, 'join'])->name('join');
    Route::post('/{group}/sair', [App\Http\Controllers\GroupController::class, 'leave'])->name('leave');
});


Route::view('bate_papo', 'bate_papo')
    ->middleware(['auth', 'verified'])
    ->name('bate_papo');


Route::get('caixa_de_mensagens', function() {
    return view('caixa_de_mensagens');
})
    ->middleware(['auth', 'verified'])
    ->name('caixa_de_mensagens');

Route::view('renovar-vip', 'renovar-vip')
    ->middleware(['auth', 'verified'])
    ->name('renovar-vip');

Route::view('mindmap', 'mindmap')
    ->middleware(['auth', 'verified'])
    ->name('mindmap');

Route::view('pesquisas', 'pesquisas')
    ->middleware(['auth', 'verified'])
    ->name('pesquisas');

Route::view('loja-virtual', 'loja-virtual')
    ->name('loja.virtual');

// Rotas da loja
Route::get('/loja', App\Livewire\Shop\ProductList::class)->name('shop.index');
Route::get('/loja/categoria/{slug}', App\Livewire\Shop\ProductList::class)->name('shop.category');
Route::get('/loja/produto/{slug}', App\Livewire\Shop\ProductDetail::class)->name('shop.product');

// Rotas da loja que requerem autenticação
Route::middleware(['auth'])->group(function () {
    Route::get('/loja/carrinho', App\Livewire\Shop\ShoppingCart::class)->name('shop.cart');
    Route::get('/loja/checkout', App\Livewire\Shop\Checkout::class)->name('shop.checkout');
    Route::get('/loja/pedido/{id}/sucesso', App\Livewire\Shop\OrderSuccess::class)->name('shop.order.success');
    Route::get('/loja/meus-pedidos', App\Livewire\Shop\UserOrders::class)->name('shop.user.orders');
    Route::get('/loja/pedido/{id}', App\Livewire\Shop\OrderDetail::class)->name('shop.order.detail');
    Route::get('/loja/lista-desejos', App\Livewire\Shop\Wishlist::class)->name('shop.wishlist');
});

// Rotas de administração
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
    Route::get('/produtos', App\Livewire\Admin\ProductManager::class)->name('admin.products');
    Route::get('/categorias', App\Livewire\Admin\CategoryManager::class)->name('admin.categories');
    Route::get('/pedidos', App\Livewire\Admin\OrderManager::class)->name('admin.orders');
    Route::get('/cupons', App\Livewire\Admin\CouponManager::class)->name('admin.coupons');
    Route::get('/usuarios', App\Livewire\Admin\UserManager::class)->name('admin.users');
    Route::get('/configuracoes', App\Livewire\Admin\Settings::class)->name('admin.settings');
    Route::get('/eventos', App\Livewire\Admin\EventManager::class)->name('admin.events');
});


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
    Volt::route('settings/preferences', 'settings.preferences-form')->name('settings.preferences');
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

    // Rotas para o histórico de pontos
    Route::get('/pontos', function () {
        return view('points-history');
    })->name('points.history');

    Route::get('/pontos/{userId}', function ($userId) {
        return view('points-history', ['userId' => $userId]);
    })->name('points.history.user');
});


// Rota para alternar curtidas (Livewire pode ser usado, mas aqui um POST simples)
Route::post('likes/toggle/{post}', [LikeController::class, 'toggle'])->name('likes.toggle')->middleware('auth');



// Rota para processar o upload da foto
Route::post('/user/upload-photo', [UserController::class, 'uploadPhoto'])->name('user.uploadPhoto');

// Rota para listar usuários (deve vir antes da rota dinâmica de perfil)
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// Rota para exibir perfil do usuário pelo username
Route::get('/{username}', function($username) {
    return view('profile-page', ['username' => $username]);
})->name('user.profile');

Route::get('post/{post}', function (Post $post) {
    return view('post.show', compact('post'));
})->name('post.show');



require __DIR__.'/auth.php';
