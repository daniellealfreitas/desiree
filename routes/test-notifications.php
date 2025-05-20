<?php

use Illuminate\Support\Facades\Route;
use App\Models\UserPoint;
use App\Models\User;

Route::get('/test-point-notifications', function () {
    // Certifique-se de que o usuário está autenticado
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $userId = auth()->id();
    
    // Adicionar alguns pontos para teste
    UserPoint::addPoints(
        $userId,
        'test',
        10,
        "Pontos de teste para notificações",
        null,
        null
    );

    return redirect()->back()->with('success', 'Notificação de pontos de teste criada!');
})->name('test.point.notifications');
