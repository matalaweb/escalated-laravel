<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/demo', function () {
    abort_unless(app()->environment('demo'), 404);

    $users = User::orderByDesc('is_admin')
        ->orderByDesc('is_agent')
        ->orderBy('id')
        ->get(['id', 'name', 'email', 'is_admin', 'is_agent']);

    return view('demo.picker', ['users' => $users]);
})->name('demo.picker');

Route::post('/demo/login/{user}', function (User $user) {
    abort_unless(app()->environment('demo'), 404);

    Auth::login($user);
    request()->session()->regenerate();

    $destination = '/support';
    if ($user->is_admin || $user->is_agent) {
        $destination = '/support/admin/tickets';
    }

    return redirect($destination);
})->name('demo.login');

Route::post('/demo/logout', function () {
    abort_unless(app()->environment('demo'), 404);

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('demo.picker');
})->name('demo.logout');
