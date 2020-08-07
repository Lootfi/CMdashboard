<?php

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

Route::post('/auth/login', 'Auth\LoginController@login');
Route::get('/auth/logout', 'Auth\LoginController@logout');


Route::group(['middleware' => ['jwt.verify:Admin,Editor,Commercial']], function () {
	Route::post('editors/{slug}/uploadAvatar', 'Editors\AvatarController@uploadAvatar');


	Route::get('role', function (Request $request) {
		return response()->json($request->user()->role);
	});
	Route::get('user', function (Request $request) {
		return response()->json($request->user(), 200);
	});
	Route::get('/auth/checkauth', 'Auth\LoginController@checkAuth');
	Route::post('/auth/password-reset', 'Auth\PasswordReset@reset');
});

// SUPER ADMIN ROUTES

Route::group(['middleware' => ['jwt.verify:Admin']], function () {

	Route::get('price', 'PriceController@show');

	Route::post('price-change', 'PriceController@change');

	Route::get('/auth/checkadmin', 'Auth\LoginController@checkAdmin');

	Route::group(['prefix' => 'artists', 'namespace' => 'Artists'], function () {
		Route::get('/{slug}/delete', 'DeleteController@delete');
		Route::post('/{slug}/edit', 'ContactTypeController@edit');
	});

	Route::group(['prefix' => 'editors', 'namespace' => 'Editors'], function () {
		Route::get('/', 'IndexController@getAll');
		Route::post('/create', 'CreateController@create');
		Route::get('/{slug}/delete', 'DeleteController@delete');
		Route::get('/{slug}/activate', 'StatusController@activate');
		Route::get('/{slug}/suspend', 'StatusController@suspend');
		Route::get('/{slug}', 'ShowController@show');
		Route::post('/{slug}/edit', 'EditController@edit');
	});

	Route::group(['prefix' => 'artists', 'namespace' => 'Artists'], function () {
		Route::post('/create', 'CreateController@create');
		Route::get('/{slug}/delete', 'DeleteController@delete');
		Route::get('/{slug}/activate', 'StatusController@activate');
		Route::get('/{slug}/suspend', 'StatusController@suspend');
		Route::post('/{slug}/edit', 'EditController@edit');
	});
});


// SUPER ADMIN OR EDITOR ROUTES

Route::group(['middleware' => ['jwt.verify:Admin,Editor']], function () {

	Route::get('/auth/check-admin-or-editor', 'Auth\LoginController@checkEditor');

	Route::group(['prefix' => 'types', 'namespace' => 'ContactTypes'], function () {
		Route::get('/', 'ContactTypeController@index');
		Route::post('/create', 'ContactTypeController@store');
		Route::get('/{slug}/delete', 'ContactTypeController@destroy');
		Route::get('/{slug}', 'ContactTypeController@show');
		Route::post('/{slug}/edit', 'ContactTypeController@update');
	});

	Route::group(['prefix' => 'contacts', 'namespace' => 'Contacts'], function () {
		Route::get('/', 'IndexController@getAll');
		Route::post('/create', 'CreateController@create');
		Route::get('/{slug}', 'ShowController@show');
		Route::post('/{slug}/edit', 'EditController@edit');
		Route::get('/{slug}/delete', 'DeleteController@delete');
		Route::post('/{slug}/uploadPicture', 'PictureController@upload');
	});
});


// SUPER ADMIN OR COMMERCIAL ADMIN ROUTES

Route::group(['middleware' => ['jwt.verify:Admin,Commercial']], function () {


	Route::get('potential-clients', 'Artists\IndexController@potentials');
	Route::get('/auth/check-admin-or-commercial', 'Auth\LoginController@checkCommercial');

	Route::group(['prefix' => 'artists', 'namespace' => 'Artists'], function () {
		Route::get('/', 'IndexController@getAll');
		Route::get('/{slug}', 'ShowController@show');
	});
});


Route::group(['prefix' => 'front', 'namespace' => 'Front'], function () {
	Route::post('validate-email', 'Auth\RegisterController@validateEmail');
	Route::post('paypal-payment-complete', 'Auth\RegisterController@paypalPaymentConfirmed');
	Route::post('setup-profile', 'Auth\RegisterController@setupProfile');
});
