<?php

Route::group(['prefix' => 'doctor', 'namespace' => 'Doctor'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/signup', 'AuthController@signup');
        Route::post('/login', 'AuthController@login');
        Route::post('/verify-otp', 'AuthController@verifyOtp');
        Route::post('/send-otp', 'AuthController@resendOTPCode');
        Route::get('/search-profiles','AuthController@searchProfiles');
        Route::post('/claim-profile','AuthController@claimProfile');

    });

    Route::group(['middleware' => 'auth:api_doctor'], function () {
        Route::get('/profile','AuthController@viewProfile');
        Route::get('/dashboard','DashboardController@index');
        Route::post('/appointments','AppointmentController@index');
        Route::post('/review','ReviewController@review');
        Route::post('/reply','ReviewController@reply');
        Route::post('/like','ReviewController@like');
        Route::post('profile/update','AuthController@updateProfile');
        Route::post('update-password','AuthController@changePassword');
        Route::post('/signout','AuthController@signOut');
    });

    Route::get('/privacy-policy','DashboardController@privacyPolicy');
    Route::get('/terms-and-conditions','DashboardController@termsAndConditions');
    Route::get('/faqs','DashboardController@faqs');
});