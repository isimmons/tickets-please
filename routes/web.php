<?php


use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/docs', function () {
    return File::get(public_path('docs/index.html'));
});

Route::get('/', function () {
    return view('welcome');
});
