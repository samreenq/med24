<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('send-notifications', function(){
    Artisan::call('notifications');
});

Auth::routes(['register' => false]);

Route::get('/clear-cache', function() {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('view:clear');
    return '<h1>Cache facade value cleared</h1>';
});

Route::get('/seed', function() {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('db:seed');
    return '<h1>Success</h1>';
});

Route::get('admin/', 'HomeController@index')->name('home');
Route::get('reset-password/{token?}','Api\PasswordResetController@reset')->name('password.reset');
Route::post('update-password','Api\PasswordResetController@updatePassword')->name('updatePassword');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function(){
	Route::group(['prefix' => 'ajax'], function (){
		Route::post('/city', 'AjaxController@getCity')->name('admin.ajax.getCity');
	});

	Route::get('admin', 'HomeController@index')->name('admin.home');
	Route::get('home', 'HomeController@index')->name('admin.home');
	Route::post('upload/image/{fileName}', 'ImageUploadController@image')->name('admin.upload.image');

	Route::group(['prefix' => 'user', 'namespace' => 'User', 'middleware' => 'permission:view customers|view admins|view gym owners'], function() {

		// Customers

		Route::group(['prefix' => 'customer', 'namespace' => 'Customer', 'middleware' => 'permission:view customers'], function() {
            Route::get('/', 'IndexController@index')->name('admin.user.customer.index')->middleware('permission:view customers');
            Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.user.customer.detail')->middleware('permission:view customers');
            Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.customer.create')->middleware('permission:create customer');
            Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.customer.create')->middleware('permission:create customer');
            Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.customer.edit')->middleware('permission:edit customer');
            Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.customer.edit')->middleware('permission:edit customer');
            Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.user.customer.update_status')->middleware('permission:edit customer');
            Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.user.customer.destroy')->middleware('permission:delete customer');
			Route::get('block', 'IndexController@block')->name('admin.user.customer.block')->middleware('permission:view blocked customers');
        });

		// End Customer

        Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'permission:view admins'], function() {
            Route::get('/', 'IndexController@index')->name('admin.user.admin.index')->middleware('permission:view admins');
            Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.admin.create')->middleware('permission:create admin');
            Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.admin.create')->middleware('permission:create admin');
            Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.admin.edit')->middleware('permission:edit admin');
            Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.admin.edit')->middleware('permission:edit admin');
            Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.user.admin.update_status')->middleware('permission:edit admin');
            Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.user.admin.destroy')->middleware('permission:delete admin');
        });

        Route::group(['prefix' => 'gym-owner', 'namespace' => 'GymOwner', 'middleware' => 'permission:view gym owners'], function() {
            Route::get('/', 'IndexController@index')->name('admin.user.gym_owner.index')->middleware('permission:view gym owners');
            Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.gym_owner.create')->middleware('permission:create gym owner');
            Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.gym_owner.create')->middleware('permission:create gym owner');
            Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.gym_owner.edit')->middleware('permission:edit gym owner');
            Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.gym_owner.edit')->middleware('permission:edit gym owner');
            Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.user.gym_owner.update_status')->middleware('permission:edit gym owner');
            Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.user.gym_owner.destroy')->middleware('permission:delete gym owner');
        });
	});

	Route::group(['prefix' => 'insurances', 'namespace' => 'Insurance'], function() {
		Route::get('/', 'InsurancesController@index')->name('admin.insurances.index');
		Route::get('add_edit', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.insurances.create');
        Route::post('add_edit', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.insurances.create');
        Route::get('add_edit/{id}', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.insurances.edit');
        Route::post('add_edit/{id}', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.insurances.edit');
		Route::get('destroy/{id}', 'InsurancesController@destroy')->name('admin.insurances.destroy');
		Route::post('importExcel', 'InsurancesController@importExcel')->name('admin.insurances.import.excel');

		Route::group(['prefix' => '{insuranceId}/plans'], function() {
			Route::get('/', 'InsurancesPlansController@index')->name('admin.insurances.plans.index');
			Route::get('add_edit', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.insurances.plans.create');
	        Route::post('add_edit', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.insurances.plans.create');
	        Route::get('add_edit/{id}', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.insurances.plans.edit');
	        Route::post('add_edit/{id}', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.insurances.plans.edit');
			Route::get('destroy/{id}', 'InsurancesPlansController@destroy')->name('admin.insurances.plans.destroy');
		});
	});

	// Doctors
	Route::group(['prefix' => 'doctors', 'namespace' => 'Doctors', 'middleware' => 'permission:view doctors'], function() {
		Route::get('/', 'IndexController@index')->name('admin.doctor.index')->middleware('permission:view doctors');
		Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.doctor.detail')->middleware('permission:view doctors');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.doctor.create')->middleware('permission:create doctor');
		Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.doctor.create')->middleware('permission:create doctor');
		Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.doctor.edit')->middleware('permission:edit doctor');
		Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.doctor.edit')->middleware('permission:edit doctor');
		Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.doctor.update_status')->middleware('permission:edit doctor');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.doctor.destroy')->middleware('permission:delete doctor');
		Route::get('isApproved/{id}', 'IndexController@isApproved')->name('admin.doctor.isApproved')->middleware('permission:edit doctor');

		Route::group(['prefix' => '{id}/timeSlots', 'middleware' => 'permission:view doctors'], function (){
			Route::get('/', 'TimeSlotsController@index')->name('admin.doctor.timeSlots.index')->middleware('permission:view doctors');
			Route::get('/add', 'TimeSlotsController@add')->name('admin.doctor.timeSlots.add')->middleware('permission:create doctor');
			Route::post('/add', 'TimeSlotsController@save')->name('admin.doctor.timeSlots.save')->middleware('permission:create doctor');
			Route::get('/delete', 'TimeSlotsController@delete')->name('admin.doctor.timeSlots.delete')->middleware('permission:delete doctor');
		});

		Route::group(['prefix' => 'claimProfile', 'middleware' => 'permission:view doctors'], function() {
			Route::get('/', 'ClaimProfilesController@index')->name('admin.doctor.claimProfiles.index')->middleware('permission:view doctors');
			Route::get('/claim/{id}', 'ClaimProfilesController@claimProfile')->name('admin.doctor.claimprofile')->middleware('permission:create doctors');
		});

		Route::group(['prefix' => 'reviews', 'middleware' => 'permission:view doctors reviews'], function(){
			Route::get('/', 'DoctorsReviewsController@index')->name('admin.doctors.reviews.index');
		});

		Route::group(['prefix' => 'cms', 'middleware' => 'permission:view cms'], function(){
			Route::get('/privacy&Policy', 'CMSController@privacy')->name('admin.doctor.cms.privacy')->middleware('permission:create cms');
			Route::post('/privacy&Policy', 'CMSController@editPrivacy')->name('admin.doctor.cms.privacy.update')->middleware('permission:create cms');
			Route::get('/terms&Conditions', 'CMSController@terms')->name('admin.doctor.cms.terms');
			Route::post('/terms&Conditions', 'CMSController@editTerms')->name('admin.doctor.cms.terms.update')->middleware('permission:create cms');
			Route::get('/faqs', 'CMSController@faqs')->name('admin.doctor.cms.faqs');
			Route::post('/edit', 'CMSController@editFaq')->name('admin.doctor.cms.faqs.edit');
			Route::post('/save', 'CMSController@saveFaq')->name('admin.doctor.cms.faqs.save');
			Route::get('/delete', 'CMSController@deleteFaq')->name('admin.doctor.cms.faqs.delete');
            Route::get('/home', 'CMSController@home')->name('admin.doctor.cms.home');
            Route::post('/home', 'CMSController@saveHome')->name('admin.doctor.cms.home.update');
		});

		Route::group(['prefix' => 'insurances'], function() {
			Route::get('/', 'InsurancesController@index')->name('admin.doctors.insurances.index');
			Route::get('add_edit', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.doctors.insurances.create');
	        Route::post('add_edit', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.doctors.insurances.create');
	        Route::get('add_edit/{id}', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.doctors.insurances.edit');
	        Route::post('add_edit/{id}', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.doctors.insurances.edit');
			Route::get('destroy/{id}', 'InsurancesController@destroy')->name('admin.doctors.insurances.destroy');
			Route::post('importExcel', 'InsurancesController@importExcel')->name('admin.doctors.insurances.import.excel');

			Route::group(['prefix' => '{insuranceId}/plans'], function() {
				Route::get('/', 'InsurancesPlansController@index')->name('admin.doctors.insurances.plans.index');
				Route::get('add_edit', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.doctors.insurances.plans.create');
		        Route::post('add_edit', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.doctors.insurances.plans.create');
		        Route::get('add_edit/{id}', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.doctors.insurances.plans.edit');
		        Route::post('add_edit/{id}', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.doctors.insurances.plans.edit');
				Route::get('destroy/{id}', 'InsurancesPlansController@destroy')->name('admin.doctors.insurances.plans.destroy');
			});
		});
	});
	// End Doctors

	// Specialities
	Route::group(['prefix' => 'specialities', 'namespace' => 'Specialities', 'middleware' => 'permission:view specialities'], function() {
		Route::get('/', 'IndexController@index')->name('admin.speciality.index')->middleware('permission:view specialities');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.speciality.create')->middleware('permission:create speciality');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.speciality.create')->middleware('permission:create speciality');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.speciality.edit')->middleware('permission:edit speciality');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.speciality.edit')->middleware('permission:edit speciality');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.speciality.destroy')->middleware('permission:delete speciality');
		Route::post('importExcel', 'IndexController@importExcel')->name('admin.speciality.import.excel')->middleware('permission:create speciality');
	});
	// End Specialities

	// Certifications
	Route::group(['prefix' => 'certifications', 'namespace' => 'Certifications', 'middleware' => 'permission:view certifications'], function() {
		Route::get('/', 'IndexController@index')->name('admin.certification.index')->middleware('permission:view certifications');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.certification.create')->middleware('permission:create certification');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.certification.create')->middleware('permission:create certification');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.certification.edit')->middleware('permission:edit certification');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.certification.edit')->middleware('permission:edit certification');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.certification.destroy')->middleware('permission:delete certification');
	});
	// End Certifications

	// Patients
	Route::group(['prefix' => 'patients', 'namespace' => 'Patients', 'middleware' => 'permission:view patients'], function() {
		Route::get('/', 'IndexController@index')->name('admin.patient.index')->middleware('permission:view patients');
		Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.patient.detail')->middleware('permission:view patients');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.patient.create')->middleware('permission:create patient');
		Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.patient.create')->middleware('permission:create patient');
		Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.patient.edit')->middleware('permission:edit patient');
		Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.patient.edit')->middleware('permission:edit patient');
		Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.patient.update_status')->middleware('permission:edit patient');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.patient.destroy')->middleware('permission:delete patient');

		Route::group(['prefix' => 'cms', 'middleware' => 'permission:view cms'], function(){
			Route::get('/privacy&Policy', 'CMSController@privacy')->name('admin.patient.cms.privacy')->middleware('permission:create cms');
			Route::post('/privacy&Policy', 'CMSController@editPrivacy')->name('admin.patient.cms.privacy.update')->middleware('permission:create cms');
			Route::get('/terms&Conditions', 'CMSController@terms')->name('admin.patient.cms.terms');
			Route::post('/terms&Conditions', 'CMSController@editTerms')->name('admin.patient.cms.terms.update')->middleware('permission:create cms');
			Route::get('/faqs', 'CMSController@faqs')->name('admin.patient.cms.faqs');
			Route::post('/edit', 'CMSController@editFaq')->name('admin.patient.cms.faqs.edit');
			Route::post('/save', 'CMSController@saveFaq')->name('admin.patient.cms.faqs.save');
			Route::get('/delete', 'CMSController@deleteFaq')->name('admin.patient.cms.faqs.delete');
		});

		Route::group(['prefix' => 'appointments'], function(){
			Route::get('/', 'AppointmentsController@index')->name('admin.patient.appointments.index');
			Route::get('/viewCallbackRequest', 'AppointmentsController@viewCallbackRequest')->name('admin.patient.appointments.callbackRequest.view');
			Route::post('getDoctorHospitals', 'AppointmentsController@getDoctorHospitals')->name('admin.patient.appointments.getDoctorHospitals');
			Route::post('getDoctorInsurances', 'AppointmentsController@getDoctorInsurances')->name('admin.patient.appointments.getDoctorInsurances');
			Route::post('getPatientFamilyMembers', 'AppointmentsController@getPatientFamilyMembers')->name('admin.patient.appointments.getPatientFamilyMembers');
			Route::post('getAvailableDays', 'AppointmentsController@getAvailableDays')->name('admin.patient.appointments.getAvailableDays');
			Route::post('getAvailableTimeSlots', 'AppointmentsController@getAvailableTimeSlots')->name('admin.patient.appointments.getAvailableTimeSlots');
			Route::post('saveAppointment', 'AppointmentsController@saveAppointment')->name('admin.patient.appointments.saveAppointment');
			Route::get('/{id}', 'AppointmentsController@patientAppointments')->name('admin.patient.appointments');
			Route::post('/edit', 'AppointmentsController@edit')->name('admin.patient.appointments.edit');

			Route::group(['prefix' => 'update'], function (){
				Route::get('/status', 'AppointmentsController@updateStatus')->name('admin.patient.appointments.update.status');
			});
		});

		Route::group(['prefix' => 'familyMembers'], function(){
			Route::group(['prefix' => 'relations'], function ()
			{
				Route::get('/', 'RelationsController@index')->name('admin.patient.familyMembers.relations.index');
				Route::get('add', 'RelationsController@add')->name('admin.patient.familyMembers.relations.add');
				Route::post('save', 'RelationsController@save')->name('admin.patient.familyMembers.relations.save');
				Route::get('delete', 'RelationsController@delete')->name('admin.patient.familyMembers.relations.delete');
			});

			Route::get('/{id}', 'FamilyMembersController@index')->name('admin.patient.familyMembers.index');
		});

		Route::group(['prefix' => 'medicalInfo'], function(){
			Route::get('/{id}', 'MedicalInfoController@index')->name('admin.patient.medicalInfo.index');
		});

		Route::group(['prefix' => 'favouriteHospitals'], function(){
			Route::get('/{id}', 'FavouriteController@index')->name('admin.patient.favourite.hospitals.index');
		});

		Route::group(['prefix' => 'favouriteDoctors'], function(){
			Route::get('/{id}', 'FavouriteController@favouriteDoctors')->name('admin.patient.favourite.doctors.index');
		});
	});
	// End Patients

	// Family Members
	Route::group(['prefix' => 'family_members', 'namespace' => 'Family_Members', 'middleware' => 'permission:view family_members'], function() {
		Route::get('/', 'IndexController@index')->name('admin.family_member.index')->middleware('permission:view family_members');
		Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.family_member.detail')->middleware('permission:view family_members');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.family_member.create')->middleware('permission:create family_member');
		Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.family_member.create')->middleware('permission:create family_member');
		Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.family_member.edit')->middleware('permission:edit family_member');
		Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.family_member.edit')->middleware('permission:edit family_member');
		Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.family_member.update_status')->middleware('permission:edit family_member');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.family_member.destroy')->middleware('permission:delete family_member');
	});
	// End Family Members

	// Medical Info
	Route::group(['prefix' => 'medical_info', 'namespace' => 'Medical_Infos', 'middleware' => 'permission:view medical_infos'], function() {
		Route::get('/', 'IndexController@index')->name('admin.medical_info.index')->middleware('permission:view medical_infos');
		Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.medical_info.detail')->middleware('permission:view medical_info');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.medical_info.create')->middleware('permission:create medical_info');
		Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.medical_info.create')->middleware('permission:create medical_info');
		Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.medical_info.edit')->middleware('permission:edit medical_info');
		Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.medical_info.edit')->middleware('permission:edit medical_info');
		Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.medical_info.update_status')->middleware('permission:edit medical_info');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.medical_info.destroy')->middleware('permission:delete medical_info');
	});
	// End Medical Info

	// Hospitals
	Route::group(['prefix' => 'hospital', 'namespace' => 'Hospital', 'middleware' => 'permission:view hospitals'], function() {
		Route::get('/', 'IndexController@index')->name('admin.hospital.index')->middleware('permission:view hospitals');
		Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.hospital.detail')->middleware('permission:view hospitals');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.hospital.create')->middleware('permission:create hospital');
		Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.hospital.create')->middleware('permission:create hospital');
		Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.hospital.edit')->middleware('permission:edit hospital');
		Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.hospital.edit')->middleware('permission:edit hospital');
		Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.hospital.update_status')->middleware('permission:edit hospital');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.hospital.destroy')->middleware('permission:delete hospital');

		Route::group(['prefix' => 'insurances'], function() {
			Route::get('/', 'InsurancesController@index')->name('admin.hospitals.insurances.index');
			Route::get('add_edit', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.hospitals.insurances.create');
	        Route::post('add_edit', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.hospitals.insurances.create');
	        Route::get('add_edit/{id}', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.hospitals.insurances.edit');
	        Route::post('add_edit/{id}', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.hospitals.insurances.edit');
			Route::get('destroy/{id}', 'InsurancesController@destroy')->name('admin.hospitals.insurances.destroy');
			Route::post('importExcel', 'InsurancesController@importExcel')->name('admin.hospitals.insurances.import.excel');

			Route::group(['prefix' => '{insuranceId}/plans'], function() {
				Route::get('/', 'InsurancesPlansController@index')->name('admin.hospitals.insurances.plans.index');
				Route::get('add_edit', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.hospitals.insurances.plans.create');
		        Route::post('add_edit', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.hospitals.insurances.plans.create');
		        Route::get('add_edit/{id}', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.hospitals.insurances.plans.edit');
		        Route::post('add_edit/{id}', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.hospitals.insurances.plans.edit');
				Route::get('destroy/{id}', 'InsurancesPlansController@destroy')->name('admin.hospitals.insurances.plans.destroy');
			});
		});
	});
	// End Hospitals

	// Specialities Hospitals
	Route::group(['prefix' => 'speciality_hospital', 'namespace' => 'Specialities_Hospitals', 'middleware' => 'permission:view specialities_hospitals'], function() {
		Route::get('/', 'IndexController@index')->name('admin.speciality_hospital.index')->middleware('permission:view specialities_hospitals');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.speciality_hospital.create')->middleware('permission:create speciality_hospital');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.speciality_hospital.create')->middleware('permission:create speciality_hospital');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.speciality_hospital.edit')->middleware('permission:edit speciality_hospital');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.speciality_hospital.edit')->middleware('permission:edit hospital_speciality');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.speciality_hospital.destroy')->middleware('permission:delete speciality_hospital');
		Route::post('importExcel', 'IndexController@importExcel')->name('admin.speciality.hospital.import.excel')->middleware('permission:create speciality_hospital');
	});
	// End Specialities Hospitals

	// Certifications Hospitals
	Route::group(['prefix' => 'certification_hospital', 'namespace' => 'Certifications_Hospitals', 'middleware' => 'permission:view certifications_hospitals'], function() {
		Route::get('/', 'IndexController@index')->name('admin.certification_hospital.index')->middleware('permission:view certifications_hospitals');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.certification_hospital.create')->middleware('permission:create certification_hospital');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.certification_hospital.create')->middleware('permission:create certification_hospital');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.certification_hospital.edit')->middleware('permission:edit certification_hospital');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.certification_hospital.edit')->middleware('permission:edit certification_hospital');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.certification_hospital.destroy')->middleware('permission:delete certification_hospital');
	});
	// End Certifications Hospitals

	// Awards Hospitals
	Route::group(['prefix' => 'award_hospital', 'namespace' => 'Awards_Hospitals', 'middleware' => 'permission:view awards_hospitals'], function() {
		Route::get('/', 'IndexController@index')->name('admin.award_hospital.index')->middleware('permission:view awards_hospitals');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.award_hospital.create')->middleware('permission:create award_hospital');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.award_hospital.create')->middleware('permission:create award_hospital');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.award_hospital.edit')->middleware('permission:edit award_hospital');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.award_hospital.edit')->middleware('permission:edit award_hospital');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.award_hospital.destroy')->middleware('permission:delete award_hospital');
	});
	// End Awards Hospitals

	// Pharmacies
	Route::group(['prefix' => 'pharmacy', 'namespace' => 'Pharmacy', 'middleware' => 'permission:view pharmacys'], function() {
		Route::get('/', 'IndexController@index')->name('admin.user.pharmacy.index')->middleware('permission:view pharmacys');
		Route::get('/detail/{user_id}', 'IndexController@detail')->name('admin.user.pharmacy.detail')->middleware('permission:view pharmacys');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.pharmacy.create')->middleware('permission:create pharmacy');
		Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.pharmacy.create')->middleware('permission:create pharmacy');
		Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.user.pharmacy.edit')->middleware('permission:edit pharmacy');
		Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.user.pharmacy.edit')->middleware('permission:edit pharmacy');
		Route::get('update-status/{id}', 'IndexController@updateStatus')->name('admin.user.pharmacy.update_status')->middleware('permission:edit pharmacy');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.user.pharmacy.destroy')->middleware('permission:delete pharmacy');
		Route::post('importExcel', 'IndexController@importExcel')->name('admin.user.pharmacy.import.excel');

		Route::group(['prefix' => 'insurances'], function() {
			Route::get('/', 'InsurancesController@index')->name('admin.pharmacies.insurances.index');
			Route::get('add_edit', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.create');
	        Route::post('add_edit', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.create');
	        Route::get('add_edit/{id}', 'InsurancesController@addEdit')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.edit');
	        Route::post('add_edit/{id}', 'InsurancesController@save')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.edit');
			Route::get('destroy/{id}', 'InsurancesController@destroy')->name('admin.pharmacies.insurances.destroy');
			Route::post('importExcel', 'InsurancesController@importExcel')->name('admin.pharmacies.insurances.import.excel');

			Route::group(['prefix' => '{insuranceId}/plans'], function() {
				Route::get('/', 'InsurancesPlansController@index')->name('admin.pharmacies.insurances.plans.index');
				Route::get('add_edit', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.plans.create');
		        Route::post('add_edit', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.plans.create');
		        Route::get('add_edit/{id}', 'InsurancesPlansController@addEdit')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.plans.edit');
		        Route::post('add_edit/{id}', 'InsurancesPlansController@save')->where('id', '[0-9]+')->name('admin.pharmacies.insurances.plans.edit');
				Route::get('destroy/{id}', 'InsurancesPlansController@destroy')->name('admin.pharmacies.insurances.plans.destroy');
			});
		});
	});
	// End Pharmacies

	// Countries & Cities
	Route::group(['prefix' => 'country', 'namespace' => 'Country', 'middleware' => 'permission:view countries'], function() {
		Route::get('/', 'IndexController@index')->name('admin.country.index')->middleware('permission:view countries');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.country.create')->middleware('permission:create country');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.country.create')->middleware('permission:create country');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.country.edit')->middleware('permission:edit country');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.country.edit')->middleware('permission:edit country');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.country.destroy')->middleware('permission:delete country');

        Route::group(['prefix' => '{country_id}/city', 'namespace' => 'City', 'middleware' => 'permission:view cities'], function() {
            Route::get('/', 'IndexController@index')->name('admin.city.index')->middleware('permission:view cities');
            Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.city.create')->middleware('permission:create city');
            Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.city.create')->middleware('permission:create city');
            Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.city.edit')->middleware('permission:edit city');
            Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.city.edit')->middleware('permission:edit city');
            Route::post('save', 'IndexController@save')->name('admin.city.save')->middleware('permission:edit city|create city');
            Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.city.destroy')->middleware('permission:delete city');
        });
	});
	// End Countries & Cities

	Route::group(['prefix' => 'role', 'namespace' => 'Roles', 'middleware' => 'permission:view roles'], function() {
		Route::get('/', 'IndexController@index')->name('admin.role.index')->middleware('permission:view roles');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.role.create')->middleware('permission:create role');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.role.create')->middleware('permission:create role');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.role.edit')->middleware('permission:edit role');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.role.edit')->middleware('permission:edit role');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.role.destroy')->middleware('permission:delete role');
	});

	Route::group(['prefix' => 'amenities', 'namespace' => 'Amenities', 'middleware' => 'permission:view amenities'], function() {
		Route::get('/', 'IndexController@index')->name('admin.amenities.index')->middleware('permission:view amenities');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.amenities.create')->middleware('permission:create amenity');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.amenities.create')->middleware('permission:create amenity');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.amenities.edit')->middleware('permission:edit amenity');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.amenities.edit')->middleware('permission:edit amenity');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.amenities.destroy')->middleware('permission:delete amenity');
	});

	Route::group(['prefix' => 'gym', 'namespace' => 'Gym', 'middleware' => 'permission:view gyms'], function() {
		Route::get('/', 'IndexController@index')->name('admin.gym.index')->middleware('permission:view gyms');
		Route::get('branches/{parent_id}', 'IndexController@branches')->name('admin.gym.branches')->middleware('permission:view gyms');
		Route::get('create/{parent_id?}', 'IndexController@create')->where('id', '[0-9]+')->name('admin.gym.create')->middleware('permission:create gym');
		Route::get('cities', 'IndexController@getCities')->name('admin.gym.cities')->middleware('permission:create gym');
        Route::post('create/{parent_id?}', 'IndexController@store')->where('id', '[0-9]+')->name('admin.gym.store')->middleware('permission:create gym');
        Route::get('edit/{id}', 'IndexController@edit')->where('id', '[0-9]+')->name('admin.gym.edit')->middleware('permission:edit gym');
        Route::post('edit/{id}', 'IndexController@update')->where('id', '[0-9]+')->name('admin.gym.update')->middleware('permission:edit gym');
        Route::get('regenerate-token/{id}', 'IndexController@regenerateToken')->where('id', '[0-9]+')->name('admin.gym.regenerate_token')->middleware('permission:edit gym');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.gym.destroy')->middleware('permission:delete gym');

		Route::group(['prefix' => '{gym_id}/classes', 'namespace' => 'Classes', 'middleware' => 'permission:view gym classes'], function() {
			Route::get('/', 'IndexController@index')->name('admin.gym.class.index')->middleware('permission:view gym classes');
	        Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.gym.class.create')->middleware('permission:create gym class');
	        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.gym.class.create')->middleware('permission:create gym class');
	        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.gym.class.edit')->middleware('permission:edit gym class');
	        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.gym.class.edit')->middleware('permission:edit gym class');
			Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.gym.class.destroy')->middleware('permission:delete gym class');
		});

        Route::group(['prefix' => '{gym_id}/images', 'namespace' => 'Images', 'middleware' => 'permission:view gym images'], function() {
            Route::get('/', 'IndexController@index')->name('admin.gym.images.index')->middleware('permission:view gym images');
            Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.gym.images.create')->middleware('permission:create gym image');
            Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.gym.images.destroy')->middleware('permission:delete gym image');
        });
	});

	Route::group(['prefix' => 'offers', 'namespace' => 'Offers', 'middleware' => 'permission:view offers'], function() {
		Route::get('/', 'IndexController@index')->name('admin.offers.index')->middleware('permission:view offers');
        Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.offers.create')->middleware('permission:create offer');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.offers.create')->middleware('permission:create offer');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.offers.edit')->middleware('permission:edit offer');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.offers.edit')->middleware('permission:edit offer');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.offers.destroy')->middleware('permission:delete offer');
	});

	Route::group(['prefix' => 'vouchers', 'namespace' => 'Vouchers', 'middleware' => 'permission:view vouchers'], function() {
		Route::get('/', 'IndexController@index')->name('admin.vouchers.index')->middleware('permission:view vouchers');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.vouchers.create')->middleware('permission:create voucher');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.vouchers.create')->middleware('permission:create voucher');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.vouchers.edit')->middleware('permission:edit voucher');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.vouchers.edit')->middleware('permission:edit voucher');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.vouchers.destroy')->middleware('permission:delete voucher');
	});

    Route::group(['prefix' => 'milestones', 'namespace' => 'Milestones', 'middleware' => 'permission:view milestones'], function() {
        Route::get('/', 'IndexController@index')->name('admin.milestones.index')->middleware('permission:view milestones');
        Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.milestones.create')->middleware('permission:create milestone');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.milestones.create')->middleware('permission:create milestone');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.milestones.edit')->middleware('permission:edit milestone');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.milestones.edit')->middleware('permission:edit milestone');
        Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.milestones.destroy')->middleware('permission:delete milestone');
    });

	Route::group(['prefix' => 'faq', 'namespace' => 'Faq', 'middleware' => 'permission:view faqs'], function() {
		Route::get('/', 'IndexController@index')->name('admin.faq.index')->middleware('permission:view faqs');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.faq.create')->middleware('permission:create faq');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.faq.create')->middleware('permission:create faq');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.faq.edit')->middleware('permission:edit faq');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.faq.edit')->middleware('permission:edit faq');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.faq.destroy')->middleware('permission:delete faq');
	});

	Route::group(['prefix' => 'banners', 'namespace' => 'Banners', 'middleware' => 'permission:view banners'], function() {
		Route::get('/', 'IndexController@index')->name('admin.banners.index')->middleware('permission:view banners');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.banners.create')->middleware('permission:create banner');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.banners.create')->middleware('permission:create banner');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.banners.edit')->middleware('permission:edit banner');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.banners.edit')->middleware('permission:edit banner');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.banners.destroy')->middleware('permission:delete banner');
	});

    Route::group(['prefix' => 'feedback', 'namespace' => 'Feedback', 'middleware' => 'permission:view feedback'], function() {
        Route::get('/', 'IndexController@index')->name('admin.feedback.index')->middleware('permission:view feedback');
    });

    Route::group(['prefix' => 'commissions', 'namespace' => 'Commissions', 'middleware' => 'permission:view commissions'], function() {
        Route::get('/', 'IndexController@index')->name('admin.commissions.index')->middleware('permission:view commissions');
    });

	Route::group(['prefix' => 'sessions', 'namespace' => 'Sessions', 'middleware' => 'permission:view sessions'], function() {
		Route::get('/', 'IndexController@sessions')->name('admin.sessions.get_sessions')->middleware('permission:view sessions');
        Route::post('/export', 'IndexController@exportSessions')->name('admin.sessions.export_sessions')->middleware('permission:export sessions');
		Route::post('/', 'IndexController@sessions')->name('admin.sessions.post_sessions')->middleware('permission:view sessions');
		Route::get('get-cards', 'IndexController@getCards')->name('admin.sessions.get_cards')->middleware('permission:scanout');
		Route::post('scan-out', 'IndexController@scanOut')->name('admin.sessions.scan_out')->middleware('permission:scanout');
		Route::get('{session_id}', 'IndexController@detail')->name('admin.sessions.detail')->middleware('permission:view sessions');
	});

	Route::group(['prefix' => 'pages', 'namespace' => 'Pages', 'middleware' => 'permission:Manage Pages'], function() {
		Route::get('toc', 'IndexController@get_toc')->name('admin.pages.get_toc')->middleware('permission:edit toc');
		Route::post('toc', 'IndexController@post_toc')->name('admin.pages.post_toc')->middleware('permission:edit toc');
		Route::get('privacy-policy', 'IndexController@get_privacy_policy')->name('admin.pages.get_privacy_policy')->middleware('permission:edit privacy_policy');
		Route::post('privacy-policy', 'IndexController@post_privacy_policy')->name('admin.pages.post_privacy_policy')->middleware('permission:edit privacy_policy');
        Route::get('newsletter', 'IndexController@newsletter')->name('admin.pages.newsletters')->middleware('permission:view newsletters');
	});

	Route::group(['prefix' => 'reports', 'namespace' => 'Reports'], function() {
		Route::get('finance', 'IndexController@finance')->name('admin.reports.get_finance')->middleware('permission:view finance');
		Route::post('finance', 'IndexController@finance')->name('admin.reports.post_finance')->middleware('permission:view finance');
        Route::get('vouchers', 'IndexController@vouchersUsage')->name('admin.reports.get_vouchers')->middleware('permission:view vouchers_usage');
        Route::post('vouchers', 'IndexController@vouchersUsage')->name('admin.reports.post_vouchers')->middleware('permission:view vouchers_usage');
    });

	Route::group(['prefix' => 'settings', 'namespace' => 'Settings', 'middleware' => 'permission:Manage Settings'], function() {
		Route::get('/', 'IndexController@get_settings')->name('admin.settings.index');
		Route::post('/', 'IndexController@post_settings')->name('admin.settings.submit');
	});

 	Route::group(['prefix' => 'notifications', 'namespace' => 'Notifications', 'middleware' => 'permission:view notifications'], function() {
	 	Route::get('/', 'IndexController@index')->name('admin.notifications.index')->middleware('permission:view notifications');
	 	Route::get('send-fcm', 'IndexController@loadFcmView')->name('admin.notification.send')->middleware('permission:send notification');
	 	Route::post('send-fcm', 'IndexController@sendFCMMessage')->name('admin.notification.submit')->middleware(['permission:send notification']);
        Route::get('get-trigger-data', 'IndexController@getTriggerData')->name('admin.notification.get_trigger_data')->middleware(['permission:send notification']);
 	});

 	Route::group(['prefix' => 'language', 'namespace' => 'Language', 'middleware' => 'permission:view languages'], function(){
		Route::get('/', 'IndexController@index')->name('admin.language.index')->middleware('permission:view languages');
		Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.language.create')->middleware('permission:create language');
        Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('admin.language.create')->middleware('permission:create language');
        Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('admin.language.edit')->middleware('permission:edit language');
        Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('admin.language.edit')->middleware('permission:edit language');
		Route::get('destroy/{id}', 'IndexController@destroy')->name('admin.language.destroy')->middleware('permission:delete language');
	});

    Route::group(['prefix' => 'logs', 'namespace' => 'Logs', 'middleware' => 'permission:view logs'], function() {
        Route::get('/', 'IndexController@index')->name('admin.logs.index')->middleware('permission:view logs');
        Route::post('/', 'IndexController@index')->name('admin.logs.view')->middleware('permission:view logs');
    });
});

include('hospitalRoutes.php');
include('site.php');
