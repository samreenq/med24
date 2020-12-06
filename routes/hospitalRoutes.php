<?php

Route::group(['prefix' => 'hospital', 'namespace' => 'Hospital', 'middleware' => 'web'], function(){
	Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function (){
		Route::get('/signIn', 'IndexController@signIn')->name('hospital.auth.signIn');
		Route::post('/authenticating', 'IndexController@authenticating')->name('hospital.auth.authenticating');
	});

	Route::group(['middleware' => 'auth:hospital'], function(){
		Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function (){
			Route::get('/signOut', 'IndexController@signOut')->name('hospital.auth.signOut');
		});

		Route::group(['prefix' => 'dashboard'], function(){
			Route::get('/','Dashboard\IndexController@index')->name('hospital.dashboard.index');
		});

		Route::group(['prefix' => 'doctors', 'namespace' => 'Doctors'], function() {
			Route::get('/', 'IndexController@index')->name('hospital.doctors.index');
			Route::get('add_edit', 'IndexController@addEdit')->where('id', '[0-9]+')->name('hospital.doctors.create');
			Route::post('add_edit', 'IndexController@save')->where('id', '[0-9]+')->name('hospital.doctors.create');
			Route::get('add_edit/{id}', 'IndexController@addEdit')->where('id', '[0-9]+')->name('hospital.doctors.edit');
			Route::post('add_edit/{id}', 'IndexController@save')->where('id', '[0-9]+')->name('hospital.doctors.edit');
			Route::get('destroy/{id}', 'IndexController@destroy')->name('hospital.doctors.destroy');
			Route::get('search', 'IndexController@search')->name('hospital.doctors.search');
			Route::get('add/{id}', 'IndexController@add')->name('hospital.doctors.add');

			Route::group(['prefix' => 'reviews', 'middleware' => 'permission:view doctors reviews'], function(){
				Route::get('/', 'DoctorsReviewsController@index')->name('hospital.doctors.reviews.index');
			});
		});

		Route::group(['prefix' => 'appointments', 'namespace' => 'Appointments'], function(){
			Route::get('/', 'IndexController@index')->name('hospital.appointments.index');
			Route::get('/viewCallbackRequest', 'IndexController@viewCallbackRequest')->name('hospital.appointments.callbackRequest.view');
			Route::post('getDoctorInsurances', 'IndexController@getDoctorInsurances')->name('hospital.appointments.getDoctorInsurances');
			Route::post('getPatientFamilyMembers', 'IndexController@getPatientFamilyMembers')->name('hospital.appointments.getPatientFamilyMembers');
			Route::post('getAvailableDays', 'IndexController@getAvailableDays')->name('hospital.appointments.getAvailableDays');
			Route::post('getAvailableTimeSlots', 'IndexController@getAvailableTimeSlots')->name('hospital.appointments.getAvailableTimeSlots');
			Route::post('saveAppointment', 'IndexController@saveAppointment')->name('hospital.appointments.saveAppointment');
			Route::post('/edit', 'IndexController@edit')->name('hospital.appointments.edit');

			Route::group(['prefix' => 'update'], function (){
				Route::get('/status', 'IndexController@updateStatus')->name('hospital.appointments.update.status');
			});
		});

		Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function (){
			Route::group(['prefix' => 'profile'], function (){
				Route::get('/', 'IndexController@index')->name('hospital.settings.profile.index');
				Route::post('/', 'IndexController@save')->name('hospital.settings.profile.save');
			});
		});
	});
});