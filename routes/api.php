<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Api', 'middleware' => 'api'], function() {
    Route::post('/forgot-password','PasswordResetController@forgotPass');
    Route::get('/hospitals','ApiController@hospitals');
    Route::get('/countries','ApiController@countries');
    Route::get('/cities','ApiController@cities');
    Route::get('/languages','ApiController@languages');
    Route::post('/insurancePlans','ApiController@insurancePlans');
    Route::post('/offers','OfferController@getAllOffers');
    Route::get('/offers-by-categories','OfferController@getAllOffersByCategories');
    Route::get('/offersByCategories','OfferController@offersByCategories');
    Route::get('/trending-offers','OfferController@getAllTrendingOffers');
    Route::get('/new-offers','OfferController@getAllNewOffers');
    Route::get('/offers-by-category/{speciality_id}','OfferController@getAllOffersByCategory');
    Route::get('/offer/{id}','OfferController@getOffer');
    Route::get('/specialties', 'ApiController@specialities');
    
    Route::group(['prefix' => 'hospital'], function (){
        Route::get('/specialties', 'ApiController@hospitalSpecialties');
        Route::get('/certifications', 'ApiController@hospitalCertifications');
        Route::get('/awards', 'ApiController@hospitalAwards');
        Route::get('/insurances', 'ApiController@hospitalInsurances');
    });

    include('api_doctors.php');
    include('api_patients.php');
});