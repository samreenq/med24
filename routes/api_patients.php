<?php

Route::group(['prefix' => 'patient', 'namespace' => 'Patient'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/signup', 'AuthController@signup');
        Route::post('/social', 'AuthController@socialAuth');
        Route::post('/login', 'AuthController@login');
        Route::post('/verify-otp', 'AuthController@verifyOtp');
        Route::post('/send-otp', 'AuthController@resendOTPCode');
    });

    Route::get('/dashboard','DashboardController@index');
    Route::post('/dashboard/search','DashboardController@search');
    Route::get('/privacy-policy','DashboardController@privacyPolicy');
    Route::get('/faqs','DashboardController@faqs');
    Route::get('/terms-and-conditions','DashboardController@termsAndConditions');
    Route::get('/hospitals','HospitalController@index');
    Route::get('/hospitals/{id}','HospitalController@hospitalDetails');
    Route::get('/filter-hospitals','HospitalController@filterHospitals');
    Route::get('/doctors','DoctorController@index');
    Route::get('/doctors/{id}','DoctorController@doctorDetails');
    Route::get('/filter-doctors','DoctorController@filterDoctors');
    Route::get('/viewReplies','ReviewController@viewReplies');

    Route::group(['middleware' => 'auth:api_patient'], function () {
        Route::get('/profile','AuthController@viewProfile');
        Route::get('/family-members','FamilyMemberController@index');
        Route::get('/relations','FamilyMemberController@relations');
        Route::post('/family-members/save','FamilyMemberController@saveFamilyMember');
        Route::post('/family-members/update','FamilyMemberController@saveFamilyMember');
        Route::post('/family-members/delete','FamilyMemberController@deleteFamilyMember');
        Route::get('/medical-info','MedicalInfoController@index');
        Route::post('/medical-info/save','MedicalInfoController@saveMedicalInfo');
        Route::post('/medical-info/update','MedicalInfoController@saveMedicalInfo');
        Route::post('/medical-info/delete','MedicalInfoController@delete');
        Route::post('/hospitals/mark','HospitalController@markHospital');
        Route::get('appointments','AppointmentController@index');
        Route::post('appointments/add','AppointmentController@addAppointment');
        Route::post('appointments/reschedule','AppointmentController@rescheduleAppointment');
        Route::get('appointments/callBackRequest','AppointmentController@viewCallBackRequest');
        Route::post('appointments/callBackRequest/add','AppointmentController@addCallBackRequest');
        Route::get('/doctor/time-slots','DoctorController@getTimeSlots');
        Route::get('/doctor/available-days','DoctorController@getAvailableDays');
        Route::post('/doctor/mark','DoctorController@saveDoctor');
        Route::get('/doctor/visited','DoctorController@visitedDoctors');
        Route::get('/reviews','ReviewController@index');
        Route::post('/review','ReviewController@review');
        Route::post('/reply','ReviewController@reply');
        Route::post('/like','ReviewController@like');
        Route::post('profile/update','AuthController@updateProfile');
        Route::post('update-password','AuthController@changePassword');
        Route::post('/signout','AuthController@signOut');
    });
});