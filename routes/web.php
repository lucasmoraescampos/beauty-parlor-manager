<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/employee/{uid}', function ($uid) {
    return Inertia::render('EmployeeCard', ['uid' => $uid]);
});
