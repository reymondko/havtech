<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/admin', 'HomeController@index');

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/dashboard', 'DashboardController@index')->middleware('App\Http\Middleware\AdminMiddleware')->name('dashboard');

//Route::get('/events', 'EventsController@index')->name('events');
Route::group(['prefix' => 'events', 'middleware' => 'App\Http\Middleware\AdminMiddleware' ], function() {
	Route::get('/admin', 'EventsController@index')->name('events');
	Route::get('/specialevents', 'EventsController@specialEvents')->name('specialEvents');
    Route::get('/generalevents', 'EventsController@generalEvents')->name('generalEvents');
    Route::get('/upcomingevents', 'EventsController@upcomingEvents')->name('upcomingEvents');
	Route::get('/archiveevents', 'EventsController@archiveEvents')->name('archiveEvents');
	Route::get('/lievents', 'EventsController@LIEvents')->name('LIEvents');

	Route::get('/deleteevents', 'EventsController@deleteEvent')->name('deleteEvent');
	//delete banner images
	Route::post('/delete-banner', 'EventsController@deleteBanner')->name('deleteBanner');
	//delete banner images
	Route::post('/delete-itinerary-file', 'EventsController@deleteItineraryFile')->name('deleteItinerary');
	//go back
	Route::post('/go-back', 'EventsController@getPrevStep')->name('getPrevStep');


	//create events pages
	Route::get('/step/{step}of{total}/create-event-overview/{event_id}', 'EventsController@createEventOverviewPage')->name('createEventOverviewPage');
	Route::get('/step/{step}of{total}/create-event-schedule/{event_id}', 'EventScheduleController@createEventSchedulePage')->name('createEventSchedulePage');
	Route::get('/step/{step}of{total}/create-event-accomodations/{event_id}', 'EventAccomodationController@createAccomodationsPage')->name('createAccomodationsPage');
	Route::get('/step/{step}of{total}/create-event-dining/{event_id}', 'EventDiningController@createDiningPage')->name('createDiningPage');
	Route::get('/step/{step}of{total}/create-event-transportation/{event_id}', 'EventTransportationController@createTransortationPage')->name('createTransortationPage');
	Route::get('/step/{step}of{total}/create-event-map/{event_id}', 'EventMapController@createMapPage')->name('createMapPage');
	Route::get('/step/{step}of{total}/create-event-travel-host/{event_id}', 'EventTravelHostFaqsController@createTravelHostAndInformationPage')->name('createTravelHostAndInformationPage');
	Route::get('/step/{step}of{total}/create-event-attendee-list/{event_id}', 'EventAttendeeListController@createAttendeeListPage')->name('createAttendeeListPage');
	Route::get('/step/{step}of{total}/create-event-photos/{event_id}', 'EventPhotosController@createEventPhotosPage')->name('createEventPhotosPage');

	//submit forms
	Route::post('/addeventoverview', 'EventsController@addEventOverview')->name('addEventOverview');
	Route::post('/addeventschedule', 'EventScheduleController@addEventSchedule')->name('addEventSchedule');
	Route::post('/addaccomodations', 'EventAccomodationController@addAccomodations')->name('addAccomodations');
	Route::post('/addeventdining', 'EventDiningController@addEventDining')->name('addEventDining');
	Route::post('/addeventtransportation', 'EventTransportationController@addEventTransportation')->name('addEventTransportation');
	Route::post('/addeventmap', 'EventsController@addEventMap')->name('addEventMap');
	Route::post('/addeventtravelhost', 'EventTravelHostFaqsController@addEventTravelHost')->name('addEventTravelHost');
	Route::post('/addeventattendeelist', 'EventsController@addEventAttendeeList')->name('addEventAttendeeList');
	Route::post('/nextStep', 'EventsController@goNextStep')->name('nextStep');
	

	//edit events pages
	Route::get('/edit-event-overview/{step}/{event_id}', 'EventsController@editEventOverview')->name('editEventOverview');
	Route::get('/edit-event-schedule/{step}/{event_id}', 'EventScheduleController@editEventSchedule')->name('editEventSchedule');
	Route::get('/edit-event-accomodations/{step}/{event_id}', 'EventAccomodationController@editEventAccomodations')->name('editEventAccomodations');
	Route::get('/edit-event-dining/{step}/{event_id}', 'EventDiningController@editEventDining')->name('editEventDining');
	Route::get('/edit-event-transportation/{step}/{event_id}', 'EventTransportationController@editEventTransportation')->name('editEventTransportation');
	Route::get('/edit-event-map/{step}/{event_id}', 'EventMapController@editEventMap')->name('editEventMap');
	Route::get('/edit-event-travel-host/{step}/{event_id}', 'EventTravelHostFaqsController@editTravelHostAndInformation')->name('editEventTravel-host');
	Route::get('/edit-event-attendee-list/{step}/{event_id}', 'EventAttendeeListController@editAttendeeListPage')->name('editEventAttendee-list');
	Route::get('/edit-event-photos/{step}/{event_id}', 'EventPhotosController@editEventPhotosPage')->name('editEventPhotos');

	//submit update forms
	Route::post('/updateeventoverview', 'EventsController@updateEventOverview')->name('updateEventOverview');
	Route::post('/updateeventschedule', 'EventScheduleController@updateEventSchedule')->name('updateEventSchedule');
	Route::post('/updateaccomodations', 'EventAccomodationController@updateAccomodations')->name('updateAccomodations');
	Route::post('/updateeventdining', 'EventDiningController@updateEventDining')->name('updateEventDining');
	Route::post('/updateeventtransportation', 'EventTransportationController@updateEventTransportation')->name('updateEventTransportation');
	Route::post('/updateeventmap', 'EventsController@updateEventMap')->name('updateEventMap');
	Route::post('/updateeventtravelhost', 'EventTravelHostFaqsController@updateEventTravelHost')->name('updateEventTravelHost');
	Route::post('/updateeventattendeelist', 'EventsController@updateEventAttendeeList')->name('updateEventAttendeeList');

	//submit delete forms
	Route::post('/deleteeventoverview', 'EventsController@deleteEventOverview')->name('deleteEventOverview');
	Route::post('/deleteeventschedule', 'EventScheduleController@deleteEventSchedule')->name('deleteEventSchedule');
	Route::post('/deleteaccomodations', 'EventAccomodationController@deleteAccomodations')->name('deleteAccomodations');
	Route::post('/deleteeventdining', 'EventDiningController@deleteEventDining')->name('deleteEventDining');
	Route::post('/deleteeventtransportation', 'EventTransportationController@deleteEventTransportation')->name('deleteEventTransportation');
	Route::post('/deleteeventmap', 'EventsController@deleteEventMap')->name('deleteEventMap');
	Route::post('/deleteeventtravelhost', 'EventTravelHostFaqsController@deleteEventTravelHost')->name('deleteEventTravelHost');
	Route::post('/deleteeventfaq', 'EventTravelHostFaqsController@deleteEventFaq')->name('deleteEventFaq');

	Route::post('/deleteeventattendeelist', 'EventsController@deleteEventAttendeeList')->name('deleteEventAttendeeList');

	//maps part
	Route::post('maps/images-save', 'EventMapController@addEventMap')->name('addEventMap');
	Route::post('maps/images-delete', 'EventMapController@destroyEventMap');
	Route::get('maps/images-show', 'EventMapController@index');

	Route::post('maps/updatemapnames', 'EventMapController@updateMapNames');

	Route::get('maps/geteventmaps', 'EventMapController@loadEventMaps');

	//Photos part
	Route::post('photos/images-save', 'EventPhotosController@addEventPhoto')->name('addEventPhoto');
	Route::post('photos/images-delete', 'EventPhotosController@destroyEventPhoto');
	Route::get('photos/images-show', 'EventPhotosController@index');

	//AttendeeList
	Route::post('attendee/attendee-list', 'EventAttendeeListController@getAttendeeList')->name('getAttendeeList');
	Route::post('attendee/invite-user', 'EventAttendeeListController@inviteUser')->name('inviteUser');
	Route::post('attendee/uninvite-user', 'EventAttendeeListController@uninviteUser')->name('uninviteUser');
	Route::post('attendee/updateeventattendeelist', 'EventAttendeeListController@updateeventAttendeelist')->name('updateeventattendeelist');
	Route::post('attendee/update-attendees', 'EventAttendeeListController@updateAttendees')->name('updateAttendees');
	Route::post('attendee/attendee-export', 'EventAttendeeListController@attendeeExport')->name('attendeeExport');
	
	Route::get('/edit-event-attendee-list/{step}/attendee-export/{event_id}', 'EventAttendeeListController@attendeeExport2')->name('attendeeExport2');

	
	Route::post('/admin-file-upload', 'HomeController@saveAdminFile')->name('saveAdminFile');


});

//settings part
Route::group(['prefix' => 'settings',  'middleware' => 'App\Http\Middleware\AdminMiddleware'], function() {
	Route::get('/', 'SettingsController@index')->name('settings');
	Route::post('/updateusersettings', 'SettingsController@updateUserSettings')->name('update_user_settings');
});

Route::group(['prefix' => 'users',  'middleware' => 'App\Http\Middleware\AdminMiddleware'], function() {
	Route::get('/', 'UsersController@index')->name('users');
	Route::get('/delete', 'UsersController@userDelete')->name('userDelete');
	
	Route::get('/makeadmin', 'UsersController@makeAdmin')->name('makeAdmin');
	
    Route::post('/add', 'UsersController@addUser')->name('add_user');
    Route::post('/updated', 'UsersController@updateUser')->name('update_user');
	Route::post('/edituser', 'UsersController@editUser')->name('edit_user');
	Route::get('/pending', 'PendingAccountsController@index')->name('pending_accounts');
});


Route::group(['prefix' => 'pending',  'middleware' => 'App\Http\Middleware\AdminMiddleware'], function() {
	Route::get('/', 'PendingAccountsController@index')->name('pending_accounts');
	Route::get('/approve/{param}', 'PendingAccountsController@approve')->name('pending_accounts_approve');
	Route::get('/delete/{param}', 'PendingAccountsController@delete')->name('pending_accounts_delete');
});


Route::post('import', 'UsersController@import')->middleware('App\Http\Middleware\AdminMiddleware')->name('import');

//notifications
Route::group(['prefix' => 'notifications',  'middleware' => 'App\Http\Middleware\AdminMiddleware'], function() {
	Route::get('/', 'NotificationsController@index')->name('Notifications');
	Route::get('/new', 'NotificationsController@NewNotification')->name('NewNotification');
	Route::get('/edit/{id}', 'NotificationsController@editNotification')->name('editNotification');
	Route::post('/add-recipient-user', 'NotificationsController@addRecipient')->name('addRecipient');
	Route::post('/remove-recipient-user', 'NotificationsController@removeRecipient')->name('removeRecipient');
	Route::post('/add-recipient-all', 'NotificationsController@addRecipientAll')->name('addRecipientAll');
	Route::post('/remove-recipient-all', 'NotificationsController@removeRecipientAll')->name('removeRecipientAll');
	Route::post('/create-notification', 'NotificationsController@CreateNotification')->name('CreateNotification');
	Route::post('/update-notification', 'NotificationsController@updateNotification')->name('updateNotification');
	Route::get('/delete', 'NotificationsController@notificationDelete')->name('notificationDelete');
	Route::post('/user-list', 'NotificationsController@userList')->name('userList');

});

//photos
Route::group(['prefix' => 'photos',  'middleware' => 'App\Http\Middleware\AdminMiddleware'], function() {
	Route::get('/{event_id}', 'PhotosController@index')->name('Photos');
	Route::post('/downloadall', 'PhotosController@downloadAllPhotos')->name('downloadAll');
	Route::post('/downloadphotos', 'PhotosController@downloadPhotos')->name('downloadPhotos');
	Route::post('/deletephotos', 'PhotosController@deletePhotos')->name('deletePhotos');

	Route::post('/approvephotos', 'PhotosController@approvePhotos')->name('approvePhotos');
	Route::post('/approveall', 'PhotosController@approveAll')->name('approveAll');
	Route::post('/deleteallphotos', 'PhotosController@deleteAllPhotos')->name('deleteAllPhotos');
	Route::view('/email-templates', 'welcome');

});

//registration
Route::group(['prefix' => 'registration',  'middleware' => 'auth'], function() {
	Route::get('/', 'EventRegistrationsController@index')->name('eventRegistration');
});

Route::get('/events-hub', 'HomeController@eventsHub');

Auth::routes();
Route::get('/user', 'WebsiteController@getUser')->name('getUser');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/password-reset-success', 'HomeController@passwordResetSuccess')->name('password-reset-success');
Route::get('/download/{file}', 'HomeController@downloadFile');
Route::get('/uploads/{file}', 'HomeController@uploadFile');
Route::get('/emailinvite', 'HomeController@emailInvite');

//frontend website routes
Route::get('/', 'WebsiteHomePageController@index')->name('homepage');
Route::post('/events/filter', 'WebsiteHomePageController@filterEvents')->name('homepage_filter');
Route::get('/events', 'WebsiteController@events')->name('eventsall');;
Route::get('/events/events', 'WebsiteController@events');
Route::get('/events/registration/{id}', 'WebsiteController@registration');
Route::post('/register', 'WebsiteController@register');
Route::get('/events/register-payment/{event_id}/{payment_id}', 'WebsiteController@registerPayment');

Route::post('/events/register-complete', 'WebsiteController@registercomplete');
Route::post('/events_filtered', 'WebsiteController@events_filtered');
Route::get('/events/{id}', 'WebsiteController@event');
Route::get('/download-mobile-app', 'WebsiteController@downloadMobileApp')->name('download-mobile-app');
Route::get('/about', 'WebsiteController@about')->name('about');
Route::get('/mission', 'WebsiteController@mission')->name('mission');
Route::get('/faqs', 'WebsiteController@faqs')->name('faqs');
Route::get('/programs', 'WebsiteController@programs')->name('programs');
Route::get('/event-types', 'WebsiteController@eventtypes')->name('event-types');
Route::get('/contact', 'WebsiteController@contact')->name('contact');
Route::post('/contact/send', 'WebsiteController@contactSend')->name('contact_send');
Route::get('/account/edit', 'WebsiteProfileController@edit')->name('edit_user_account');
Route::post('/account/edit/save', 'WebsiteProfileController@saveEdit')->name('save_edit_user_account');

Route::post('/account-registration', 'WebsiteRegisterAccountController@create')->name('create_account');
Route::get('/account-registration-success', 'WebsiteRegisterAccountController@success')->name('create_account_success');


