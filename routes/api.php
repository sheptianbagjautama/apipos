<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('register', 'JWTAuthController@register');
    Route::post('login', 'JWTAuthController@login');
    Route::post('logout', 'JWTAuthController@logout');
    Route::post('refresh', 'JWTAuthController@refresh');
    Route::get('profile', 'JWTAuthController@profile');
});

Route::group([
    'middleware' => 'api',
], function () {
    Route::resource('products', 'ProductController')->except('create','edit');
    Route::resource('orders', 'OrderController')->except('create','edit','update','delete','show');
    Route::resource('categories', 'CategoryController')->except('create','edit');
    Route::resource('subcategories', 'SubcategoryController')->except('create','edit');
    Route::get('search-subcategory/{sub_category_id}', 'ProductController@searchBySubcategory')->name('search.product.subcategory');
    Route::get('search-category/{category_id}', 'ProductController@searchByCategory')->name('search.product.category');
    Route::get('search-name/{name}', 'ProductController@searchByName')->name('search.product.name');
});

Route::get('testing/{id}', function ($id) {
    return 'Bambang'.$id;
});