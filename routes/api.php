<?php
Route::post('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/user', function () {
    return Auth::user();
})->name('user');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Add new photo
Route::post('/photo/new', 'PhotoController@create')->name('photo.create');

// Edit photo
Route::post('/photo/{id}/edit', 'PhotoController@update')->name('photo.update');

// Token refresh
Route::get('/refresh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();
    return response()->json();
});
