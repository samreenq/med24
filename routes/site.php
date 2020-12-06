<?php
Route::get('/', 'Frontend\HomeController@index')->name('site.index');

Route::get('/privacy-policy', 'Frontend\HomeController@privacy__policy')->name('site.privacy-policy');
Route::get('/term-and-condition', 'Frontend\HomeController@term__condition')->name('site.term-and-condition');
Route::get('/faq', 'Frontend\HomeController@Faq')->name('site.faq');
Route::get('/contact-us', 'Frontend\HomeController@contactUs')->name('site.contact-us');
Route::get('/about-us', 'Frontend\HomeController@aboutUs');
//Route::get('/hospital-overview', 'Frontend\HomeController@hospital__overview')->name('site.user.hospital-overview');
//Route::get('/doctor-reviews', 'Frontend\HomeController@doctor__reviews')->name('site.user.doctor-reviews');

Route::get('/special-offer', 'Frontend\HomeController@special__offer')->name('site.special-offer');
Route::get('/special-offer-description', 'Frontend\HomeController@special__offer__description')->name('site.special-offer-description');
Route::get('/select-doctor', 'Frontend\HomeController@select__doctor')->name('site.user.select-doctor');
Route::get('/add-detail', 'Frontend\HomeController@add__detail')->name('site.user.add-detail');
Route::get('/select-family', 'Frontend\HomeController@select__family')->name('site.user.select-family');
Route::get('/insurance-profile', 'Frontend\HomeController@insurance__profile')->name('site.user.insurance-profile');
//Route::get('/favourite-doctor', 'Frontend\HomeController@favourite__doctor')->name('site.user.favourite-doctor');
Route::get('/favourite-hospital', 'Frontend\HomeController@favourite__hospital')->name('site.user.favourite-hospital');

Route::get('/signup','Frontend\AuthController@register');
Route::post('/signup-submit','Frontend\AuthController@signupSubmit');
Route::get('/verify-user/{user_id}','Frontend\AuthController@verifyUser');
Route::post('/verify-email','Frontend\AuthController@verifyEmail');
Route::get('/logout','Frontend\AuthController@logout');
Route::get('/sign-in','Frontend\AuthController@Login');
Route::post('/login-submit','Frontend\AuthController@LoginSubmit');

Route::get('/edit-profile', 'Frontend\PatientController@edit_profile');
Route::post('/update-profile', 'Frontend\PatientController@updateProfile');
Route::get('/change-password', 'Frontend\PatientController@changePassword');
Route::post('/update-password', 'Frontend\AuthController@changePassword');
Route::get('/emirate', 'Frontend\PatientController@emirate')->name('site.user.emirate');
Route::post('/upload-emirate', 'Frontend\PatientController@saveEmirate');
Route::get('/insurance-card', 'Frontend\PatientController@insuranceCard');
Route::post('/upload-insurance', 'Frontend\PatientController@saveInsuranceCard');
Route::get('/add-family', 'Frontend\FamilyMemberController@addFamily');
Route::post('/add-family-member', 'Frontend\FamilyMemberController@addFamilyMember');
Route::get('/list-family-member', 'Frontend\FamilyMemberController@listing');
Route::get('/ajax-family-member', 'Frontend\FamilyMemberController@ajaxListing');
Route::get('/health-record', 'Frontend\HomeController@health_record')->name('site.user.health-record');
Route::get('/favourite-doctor', 'Frontend\DoctorController@favourite');
Route::get('/favourite-hospital', 'Frontend\HospitalController@favourite');


Route::get('/hospital/{id}', 'Frontend\HospitalController@getDetail');
Route::get('/hospital/{id}/is_book/{is_book}', 'Frontend\HospitalController@getDetail');
Route::get('/offer/{id}', 'Frontend\OfferController@getDetail');
Route::get('/special-offers', 'Frontend\OfferController@getAllSpecial');
Route::get('/offers', 'Frontend\OfferController@getAll');
Route::get('/offers/category_id/{category_id}', 'Frontend\OfferController@getAll');

Route::get('/doctor-profile/{id}', 'Frontend\DoctorController@profile');
Route::get('/doctor-reviews/{id}', 'Frontend\DoctorController@reviews');
Route::get('/patient-experience/{id}', 'Frontend\DoctorController@patientExperience');
Route::get('/affiliated-hospital/{id}', 'Frontend\DoctorController@affiliatedHospital');
Route::post('/add-review/{id}', 'Frontend\DoctorController@submitReview');
Route::post('/add-to-fav', 'Frontend\AjaxController@addToFav');
Route::post('/add-hospital-review','Frontend\AjaxController@reviewHospital');
Route::get('/e-prescriptions', 'Frontend\PatientController@e__prescriptions');
Route::get('/add-prescriptions', 'Frontend\PatientController@add__prescriptions');
Route::get('/current-appointment', 'Frontend\PatientController@current__appointment');
Route::get('/past-appointment', 'Frontend\PatientController@past__appointment');
Route::get('/book-appointment/hospital/{hospital_id}/doctor/{doctor_id}', 'Frontend\AppointmentController@bookAppointment');
Route::get('/book-appointment/doctor/{doctor_id}', 'Frontend\AppointmentController@bookAppointment');
Route::get('/book-appointment/hospital/{hospital_id}', 'Frontend\AppointmentController@bookAppointment');
Route::post('/save-appointment', 'Frontend\AppointmentController@saveAppointment');

Route::get('/health-record', 'Frontend\MedicalController@healthRecord');
Route::get('/add-health-record', 'Frontend\MedicalController@addHealthRecord');
Route::post('/save-health-record', 'Frontend\MedicalController@saveHealthRecord');

Route::get('/medical-info', 'Frontend\MedicalController@medicalCondition');
Route::get('/add-medical-info', 'Frontend\MedicalController@addMedicalInfo');
Route::post('/save-medical-info', 'Frontend\MedicalController@saveMedicalInfo');

Route::get('/doctors', 'Frontend\DoctorController@getAll');
Route::get('/hospitals', 'Frontend\HospitalController@getAll');
Route::get('/review-like/doctor_id/{doctor_id}/id/{id}/type/{type}', 'Frontend\ReviewController@likeReview');
Route::get('/review-like/hospital_id/{hospital_id}/id/{id}/type/{type}', 'Frontend\ReviewController@likeReview');

Route::get('/reply-like/doctor_id/{doctor_id}/id/{id}/reply_id/{reply_id}/type/{type}', 'Frontend\ReviewController@likeReviewReply');
Route::get('/reply-like/hospital_id/{hospital_id}/id/{id}/reply_id/{reply_id}/type/{type}', 'Frontend\ReviewController@likeReviewReply');
Route::post('/add-review-reply', 'Frontend\ReviewController@addReviewReply');
Route::get('/search', 'Frontend\HomeController@search');
Route::post('/submit-contact-us', 'Frontend\HomeController@submitContactUs');

Route::get('/login/{provider}', 'Frontend\SocialController@redirectToProvider');
Route::get('/login/{provider}/callback', 'Frontend\SocialController@handleProviderCallback');
