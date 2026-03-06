<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/callback', function (Illuminate\Http\Request $request) {
//     $ssoCode = $request->query('code');
//     return redirect(url('/') . '?code=' . urlencode($ssoCode));
// });

Route::get('/{any?}', function () {
    return view('admin');
})->where('any', '.*');

Route::get('/', function () {
    return view('login');
});



// Route::get('/_b/{any?}', function () {
//     return view('app');
// })->where('any', '.*');

// Route::get('/_a/{any?}', function () {
//     return view('admin');
// })->where('any', '.*');
