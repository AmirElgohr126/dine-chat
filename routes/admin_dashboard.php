
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboards\DashboardAdmin\GenerateUrlChairs\GenerateUrlController;

// Define a route for the ChairsBasedOnRestaurant method
Route::get('/chairs/urls', [GenerateUrlController::class, 'ChairsBasedOnRestaurant']);
