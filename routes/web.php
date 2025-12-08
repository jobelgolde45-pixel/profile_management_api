<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/xml', function () {
    return view('xml');
})->name('xml.page');
