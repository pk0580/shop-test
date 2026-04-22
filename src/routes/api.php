<?php

use App\Modules\Product\Presentation\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', ProductController::class);
