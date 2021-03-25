<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Apartment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Sponsor;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('search/apartments','Api\ApiApartmentController@search');
Route::get('services', 'Api\ServiceController@index');

Route::post('user/apartments','Api\ApiApartmentController@index');
Route::post('user/apartments/delete','Api\ApiApartmentController@destroy');
Route::post('user/info','Api\ApiUserInfoController@show');

Route::get('user/apartments/getservices','Api\ApiApartmentController@returnServices');
Route::get('user/apartments/getsponsortypes','Api\ApiApartmentController@returnSponsorTypes');

Route::post('user/apartments/getapartment','Api\ApiApartmentController@edit');

Route::post('user/apartments/submit','Api\ApiApartmentController@submit');
Route::post('user/apartments/update','Api\ApiApartmentController@reupdate');
