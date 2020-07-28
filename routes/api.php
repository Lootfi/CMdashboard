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


Route::group(['middleware' => ['jwt.verify']], function () {

	Route::get('/auth/checkAuthToken', 'Auth\LoginController@checkAuth');
	Route::post('/auth/password-reset', 'Auth\PasswordReset@reset');

	Route::group(['prefix' => 'contacts', 'namespace' => 'Contacts'], function () {
		Route::get('/', 'IndexController@getAll');
		Route::post('/create', 'CreateController@create');
		Route::get('/{slug}', 'ShowController@show');
		Route::post('/{slug}/edit', 'EditController@edit');
		Route::get('/{slug}/delete', 'DeleteController@delete');
		Route::post('/{slug}/uploadPicture', 'PictureController@upload');
	});

	Route::group(['prefix' => 'editors', 'namespace' => 'Editors', 'middleware' => ['admin']], function () {
		Route::get('/', 'IndexController@getAll');
		Route::post('/create', 'CreateController@create');
		Route::get('/{slug}/delete', 'DeleteController@delete');
		Route::get('/{slug}/activate', 'StatusController@activate');
		Route::get('/{slug}/suspend', 'StatusController@suspend');
		Route::get('/{slug}', 'ShowController@show')->withoutMiddleware('admin');
		Route::post('/{slug}/edit', 'EditController@edit')->withoutMiddleware('admin');
		Route::post('/{slug}/uploadAvatar', 'AvatarController@uploadAvatar')->withoutMiddleware('admin');
	});
});
