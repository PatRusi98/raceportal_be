<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/login', function (Request $request) {
   return $request->user() ;
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', 'App\Http\Controllers\Api\V1\LoginController@login');
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('sign-up', 'App\Http\Controllers\Api\V1\LoginController@signUp');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('refresh-token', 'App\Http\Controllers\Api\V1\LoginController@refreshToken');
});

//Route::group(['middleware' => 'auth:sanctum'], function (){

    //region Logout
    Route::group(['prefix' => 'v1'], function () {
        Route::get('logout', 'App\Http\Controllers\Api\V1\LoginController@logout');
    });
    //endregion

    //region Series
    Route::group(['prefix' => 'v1'], function () {
        Route::get('series', 'App\Http\Controllers\Api\V1\SeriesController@getAll'); //DONE when there is class allocated
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/active', 'App\Http\Controllers\Api\V1\SeriesController@getAllActive'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{id}/events', 'App\Http\Controllers\Api\V1\SeriesController@getAllEvents'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{id}', 'App\Http\Controllers\Api\V1\SeriesController@get'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('series', 'App\Http\Controllers\Api\V1\SeriesController@store');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('series/{id}', 'App\Http\Controllers\Api\V1\SeriesController@update');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('series/{id}', 'App\Http\Controllers\Api\V1\SeriesController@delete');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('series/image/{id}', 'App\Http\Controllers\Api\V1\SeriesController@uploadImage');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{id}/entry', 'App\Http\Controllers\Api\V1\SeriesController@getAllEntries'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{id}/export/jlkbsd15fd55dcs151211z47we8', 'App\Http\Controllers\Api\V1\SeriesController@getAllEntriesInCsv');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{id}/entry-list/jlkbsd15fd55dcs151211z47we8', 'App\Http\Controllers\Api\V1\SeriesController@getEntryList');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{seriesId}/entry/{id}', 'App\Http\Controllers\Api\V1\SeriesController@getEntry'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('series/{seriesId}/entry', 'App\Http\Controllers\Api\V1\SeriesController@registerEntry');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('series/{seriesId}/entry/{id}', 'App\Http\Controllers\Api\V1\SeriesController@editEntry');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('series/{seriesId}/entry/{id}/approve', 'App\Http\Controllers\Api\V1\SeriesController@approveEntry');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('series/{seriesId}/entry/image/{id}', 'App\Http\Controllers\Api\V1\SeriesController@uploadImageForEntry');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('series/{id}/standings', 'App\Http\Controllers\Api\V1\SeriesController@getStandings');
    });
//endregion

    //region Scoring FINISHED
    Route::group(['prefix' => 'v1'], function () {
        Route::get('scoring', 'App\Http\Controllers\Api\V1\ScoringController@getAll');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('scoring/{id}', 'App\Http\Controllers\Api\V1\ScoringController@get');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('scoring/{id}', 'App\Http\Controllers\Api\V1\ScoringController@delete');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('scoring', 'App\Http\Controllers\Api\V1\ScoringController@store');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('scoring/{id}', 'App\Http\Controllers\Api\V1\ScoringController@update');
    });
    //endregion

    //region Cars FINISHED
    Route::group(['prefix' => 'v1'], function () {
        Route::get('cars', 'App\Http\Controllers\Api\V1\CarController@getAll');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('cars/{id}', 'App\Http\Controllers\Api\V1\CarController@get');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('cars/{id}', 'App\Http\Controllers\Api\V1\CarController@delete');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('cars', 'App\Http\Controllers\Api\V1\CarController@store');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('cars/{id}', 'App\Http\Controllers\Api\V1\CarController@update');
    });
    //endregion

    //region Licenses FINISHED
    Route::group(['prefix' => 'v1'], function () {
        Route::get('licenses', 'App\Http\Controllers\Api\V1\LicenseController@getAll');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('licenses/{id}', 'App\Http\Controllers\Api\V1\LicenseController@get');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('licenses/{id}', 'App\Http\Controllers\Api\V1\LicenseController@delete');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('licenses', 'App\Http\Controllers\Api\V1\LicenseController@store');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('licenses/{id}', 'App\Http\Controllers\Api\V1\LicenseController@update');
    });
    //endregion

    //region Tracks FINISHED
    Route::group(['prefix' => 'v1'], function () {
        Route::get('tracks', 'App\Http\Controllers\Api\V1\TrackController@getAll');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('tracks/{id}', 'App\Http\Controllers\Api\V1\TrackController@get');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('tracks/{id}', 'App\Http\Controllers\Api\V1\TrackController@delete');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('tracks', 'App\Http\Controllers\Api\V1\TrackController@store');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('tracks/{id}', 'App\Http\Controllers\Api\V1\TrackController@update');
    });
    //endregion

    //region Car-Class
    Route::group(['prefix' => 'v1'], function () {
        Route::delete('car-class/{id}', 'App\Http\Controllers\Api\V1\CarClassController@delete'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('car-class', 'App\Http\Controllers\Api\V1\CarClassController@store');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('car-class/{id}', 'App\Http\Controllers\Api\V1\CarClassController@update');
    });
    //endregion

    //region Users TODO
    Route::group(['prefix' => 'v1'], function () {
        Route::get('users', 'App\Http\Controllers\Api\V1\UserController@getAll');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('users/{id}', 'App\Http\Controllers\Api\V1\UserController@get');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('users/{id}', 'App\Http\Controllers\Api\V1\UserController@update');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('users/{id}/add-license/{licenseId}', 'App\Http\Controllers\Api\V1\UserController@addLicense');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('users/{id}/remove-license/{licenseId}', 'App\Http\Controllers\Api\V1\UserController@removeLicense');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('users/avatar/{id}', 'App\Http\Controllers\Api\V1\UserController@uploadAvatar');
    });
    //endregion

    //region Images TODO
    Route::group(['prefix' => 'v1'], function () {
        Route::get('images/{filename}', 'App\Http\Controllers\Api\V1\ImageProviderController@getAvatar');
    });
    //endregion

    //region Events
    Route::group(['prefix' => 'v1'], function () {
        Route::get('events/upcoming', 'App\Http\Controllers\Api\V1\EventController@getUpcoming');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('events/{id}', 'App\Http\Controllers\Api\V1\EventController@get');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('events', 'App\Http\Controllers\Api\V1\EventController@create');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::put('events/{id}', 'App\Http\Controllers\Api\V1\EventController@update');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('events/{id}', 'App\Http\Controllers\Api\V1\EventController@delete'); //DONE
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('events/image/{id}', 'App\Http\Controllers\Api\V1\EventController@uploadImage');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('events/result/{id}', 'App\Http\Controllers\Api\V1\EventController@uploadResult');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::get('events/result/{id}', 'App\Http\Controllers\Api\V1\EventController@getResult');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::delete('events/result/{id}/{idSession}', 'App\Http\Controllers\Api\V1\EventController@deleteSession');
    });

    Route::group(['prefix' => 'v1'], function () {
        Route::post('events/result/{id}/penalty/{sessionId}/{resultId}', 'App\Http\Controllers\Api\V1\EventController@addPenalty');
    });
    //endregion

//});


