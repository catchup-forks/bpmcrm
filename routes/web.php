<?php

use ProcessMaker\Http\Controllers\Api\Requests\RequestsController;

Route::group(['middleware' => ['auth', 'authorize']], function () {

// Routes related to Authentication (password reset, etc)
// Auth::routes();
    Route::namespace('Admin')->prefix('admin')->group(function () {
        Route::resource('about', 'AboutController');
        Route::resource('groups', 'GroupController')->only(['index', 'edit', 'show']);
        Route::resource('preferences', 'PreferenceController');
        Route::resource('users', 'UserController');
    });

    Route::namespace('Process')->prefix('processes')->group(function () {
        Route::resource('environment-variables', 'EnvironmentVariablesController');
        Route::resource('documents', 'DocumentController');
        Route::resource('screens', 'ScreenController');
        Route::resource('screen-builder', 'ScreenBuilderController')->parameters([
            'screen-builder' => 'screen'
        ])->only(['edit']);
        Route::resource('scripts', 'ScriptController');
        Route::resource('categories', 'ProcessCategoryController')->parameters([
            'categories' => 'processCategory'
        ]);
    });

    Route::get('/admincp', 'HomeController@index')->name('admincp.dashboard');
    Route::resource('tickets', 'TicketsController');
	Route::get('tickets/tickets', ['as'=> 'tickets.tickets.index', 'uses' => 'Tickets\TicketController@index']);
	Route::post('tickets/tickets', ['as'=> 'tickets.tickets.store', 'uses' => 'Tickets\TicketController@store']);
	Route::get('tickets/tickets/create', ['as'=> 'tickets.tickets.create', 'uses' => 'Tickets\TicketController@create']);
	Route::put('tickets/tickets/{tickets}', ['as'=> 'tickets.tickets.update', 'uses' => 'Tickets\TicketController@update']);
	Route::patch('tickets/tickets/{tickets}', ['as'=> 'tickets.tickets.update', 'uses' => 'Tickets\TicketController@update']);
	Route::delete('tickets/tickets/{tickets}', ['as'=> 'tickets.tickets.destroy', 'uses' => 'Tickets\TicketController@destroy']);
	Route::get('tickets/tickets/{tickets}', ['as'=> 'tickets.tickets.show', 'uses' => 'Tickets\TicketController@show']);
	Route::get('tickets/tickets/{tickets}/edit', ['as'=> 'tickets.tickets.edit', 'uses' => 'Tickets\TicketController@edit']);
    Route::resource('projects', 'ProjectsController');



	Route::namespace('Crm')->prefix('crm')->group(function () {
		Route::resource('relations', 'CrmController');
		Route::get('relations', ['as'=> 'crm.relations.index', 'uses' => 'RelationController@index']);
		Route::post('relations', ['as'=> 'crm.relations.store', 'uses' => 'RelationController@store']);
		Route::get('relations/create', ['as'=> 'crm.relations.create', 'uses' => 'RelationController@create']);
		Route::put('relations/{relations}', ['as'=> 'crm.relations.update', 'uses' => 'RelationController@update']);
		Route::patch('relations/{relations}', ['as'=> 'crm.relations.update', 'uses' => 'RelationController@update']);
		Route::delete('relations/{relations}', ['as'=> 'crm.relations.destroy', 'uses' => 'RelationController@destroy']);
		Route::get('relations/{relations}', ['as'=> 'crm.relations.show', 'uses' => 'RelationController@show']);
		Route::get('relations/{relations}/edit', ['as'=> 'crm.relations.edit', 'uses' => 'RelationController@edit']);
		
		
		Route::get('relationAddresses', ['as'=> 'crm.relationAddresses.index', 'uses' => 'RelationAddressController@index']);
		Route::post('relationAddresses', ['as'=> 'crm.relationAddresses.store', 'uses' => 'RelationAddressController@store']);
		Route::get('relationAddresses/create', ['as'=> 'crm.relationAddresses.create', 'uses' => 'RelationAddressController@create']);
		Route::put('relationAddresses/{relationAddresses}', ['as'=> 'crm.relationAddresses.update', 'uses' => 'RelationAddressController@update']);
		Route::patch('relationAddresses/{relationAddresses}', ['as'=> 'crm.relationAddresses.update', 'uses' => 'RelationAddressController@update']);
		Route::delete('relationAddresses/{relationAddresses}', ['as'=> 'crm.relationAddresses.destroy', 'uses' => 'RelationAddressController@destroy']);
		Route::get('relationAddresses/{relationAddresses}', ['as'=> 'crm.relationAddresses.show', 'uses' => 'RelationAddressController@show']);
		Route::get('relationAddresses/{relationAddresses}/edit', ['as'=> 'crm.relationAddresses.edit', 'uses' => 'RelationAddressController@edit']);
		
		
		Route::get('relationCommunications', ['as'=> 'crm.relationCommunications.index', 'uses' => 'RelationCommunicationController@index']);
		Route::post('relationCommunications', ['as'=> 'crm.relationCommunications.store', 'uses' => 'RelationCommunicationController@store']);
		Route::get('relationCommunications/create', ['as'=> 'crm.relationCommunications.create', 'uses' => 'RelationCommunicationController@create']);
		Route::put('relationCommunications/{relationCommunications}', ['as'=> 'crm.relationCommunications.update', 'uses' => 'RelationCommunicationController@update']);
		Route::patch('relationCommunications/{relationCommunications}', ['as'=> 'crm.relationCommunications.update', 'uses' => 'RelationCommunicationController@update']);
		Route::delete('relationCommunications/{relationCommunications}', ['as'=> 'crm.relationCommunications.destroy', 'uses' => 'RelationCommunicationController@destroy']);
		Route::get('relationCommunications/{relationCommunications}', ['as'=> 'crm.relationCommunications.show', 'uses' => 'RelationCommunicationController@show']);
		Route::get('relationCommunications/{relationCommunications}/edit', ['as'=> 'crm.relationCommunications.edit', 'uses' => 'RelationCommunicationController@edit']);
		
		
		Route::get('relationEmailaddresses', ['as'=> 'crm.relationEmailaddresses.index', 'uses' => 'RelationEmailaddressController@index']);
		Route::post('relationEmailaddresses', ['as'=> 'crm.relationEmailaddresses.store', 'uses' => 'RelationEmailaddressController@store']);
		Route::get('relationEmailaddresses/create', ['as'=> 'crm.relationEmailaddresses.create', 'uses' => 'RelationEmailaddressController@create']);
		Route::put('relationEmailaddresses/{relationEmailaddresses}', ['as'=> 'crm.relationEmailaddresses.update', 'uses' => 'RelationEmailaddressController@update']);
		Route::patch('relationEmailaddresses/{relationEmailaddresses}', ['as'=> 'crm.relationEmailaddresses.update', 'uses' => 'RelationEmailaddressController@update']);
		Route::delete('relationEmailaddresses/{relationEmailaddresses}', ['as'=> 'crm.relationEmailaddresses.destroy', 'uses' => 'RelationEmailaddressController@destroy']);
		Route::get('relationEmailaddresses/{relationEmailaddresses}', ['as'=> 'crm.relationEmailaddresses.show', 'uses' => 'RelationEmailaddressController@show']);
		Route::get('relationEmailaddresses/{relationEmailaddresses}/edit', ['as'=> 'crm.relationEmailaddresses.edit', 'uses' => 'RelationEmailaddressController@edit']);

		Route::get('customers', ['as'=> 'crm.customers.index', 'uses' => 'CustomersController@index']);
		Route::post('customers', ['as'=> 'crm.customers.store', 'uses' => 'CustomersController@store']);
		Route::get('customers/create', ['as'=> 'crm.customers.create', 'uses' => 'CustomersController@create']);
		Route::put('customers/{customers}', ['as'=> 'crm.customers.update', 'uses' => 'CustomersController@update']);
		Route::patch('customers/{customers}', ['as'=> 'crm.customers.update', 'uses' => 'CustomersController@update']);
		Route::delete('customers/{customers}', ['as'=> 'crm.customers.destroy', 'uses' => 'CustomersController@destroy']);
		Route::get('customers/{customers}', ['as'=> 'crm.customers.show', 'uses' => 'CustomersController@show']);
		Route::get('customers/{customers}/edit', ['as'=> 'crm.customers.edit', 'uses' => 'CustomersController@edit']);

		Route::get('vendors', ['as'=> 'crm.vendors.index', 'uses' => 'VendorsController@index']);
		Route::post('vendors', ['as'=> 'crm.vendors.store', 'uses' => 'VendorsController@store']);
		Route::get('vendors/create', ['as'=> 'crm.vendors.create', 'uses' => 'VendorsController@create']);
		Route::put('vendors/{vendors}', ['as'=> 'crm.vendors.update', 'uses' => 'VendorsController@update']);
		Route::patch('vendors/{vendors}', ['as'=> 'crm.vendors.update', 'uses' => 'VendorsController@update']);
		Route::delete('vendors/{vendors}', ['as'=> 'crm.vendors.destroy', 'uses' => 'VendorsController@destroy']);
		Route::get('vendors/{vendors}', ['as'=> 'crm.vendors.show', 'uses' => 'VendorsController@show']);
		Route::get('vendors/{vendors}/edit', ['as'=> 'crm.vendors.edit', 'uses' => 'VendorsController@edit']);
    });




    Route::resource('processes', 'ProcessController');
    Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::get('profile/{id}', 'ProfileController@show');
    // Ensure our modeler loads at a distinct url
    Route::get('modeler/{process}', 'Process\ModelerController')->name('modeler');

    Route::get('/', 'HomeController@index')->name('home');

    Route::get('requests/{type}', 'RequestController@index')
        ->where('type', 'all|in_progress|completed')
        ->name('requests_by_type');
    Route::resource('requests', 'RequestController')->only([
        'index', 'show'
    ]);

    Route::resource('tasks', 'TaskController');
});

// Add our broadcasting routes
Broadcast::routes();

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

$this->get('password/success', function () {
    return view('auth.passwords.success', ['title' => __('Password Reset')]);
})->name('password-success');
