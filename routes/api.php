<?php
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/user', function () {
    return Auth::user();
})->name('user');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Token refresh
Route::get('/refresh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();
    return response()->json();
});
