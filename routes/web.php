<?php

use Illuminate\Support\Facades\Route;

// Route web biasa
Route::get('/', function () {
    return view('welcome');
});
