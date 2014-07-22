<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


// Filter on Dashboard
Entrust::routeNeedsRole( 'dashboard', array('Administrator','Procurement Personnel','Requisitioner'), Redirect::to('/'), false );

// Filter for User Crud Module
Entrust::routeNeedsRole( 'user/create', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'user/edit/*', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'user/view', array('Administrator'), Redirect::to('/') );

	// User Exra Modules
	Entrust::routeNeedsRole( 'user/confirm/*', array('Administrator'), Redirect::to('/') );
	Entrust::routeNeedsRole( 'user/forgot_password', array('Administrator'), Redirect::to('/') );
	Entrust::routeNeedsRole( 'user/reset_password/*', array('Administrator'), Redirect::to('/') );
	Entrust::routeNeedsRole( 'user/confirm/*', array('Administrator'), Redirect::to('/') );
	Entrust::routeNeedsRole( 'user/delete', array('Administrator'), Redirect::to('/') );
	Entrust::routeNeedsRole( 'user/activate', array('Administrator'), Redirect::to('/') );

// Filer for Office Module
Entrust::routeNeedsRole( 'offices', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'offices/delete/*', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'offices/create', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'offices/*', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'offices/*/edit', array('Administrator'), Redirect::to('/') );

// Filter for Purchase Request
Entrust::routeNeedsRole( 'purchaseRequest/view', array('Administrator','Procurement Personnel','Requisitioner'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'purchaseRequest/create', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'purchaseRequest/edit', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'purchaseRequest/vieweach/*', array('Administrator','Procurement Personnel','Requisitioner'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'purchaseRequest/edit/*', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );

// Image Upload 

Entrust::routeNeedsRole( 'back', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'pr_imageupload', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'attach/*', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'pr_id', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'resultstest', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
 

// Filter for Workflow Module
Entrust::routeNeedsRole( 'workflow/below-fifty', array('Administrator','Procurement Personnel','Requisitioner'), Redirect::to('/'), false );

// Filter for JAN Routes

Entrust::routeNeedsRole( 'workflow/belowFifty', array('Administrator'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'workflow/aboveFifty', array('Administrator'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'workflow/workflow/aboveFive', array('Administrator'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'workflow', array('Administrator'), Redirect::to('/'), false );


// Filter for Designations
Entrust::routeNeedsRole( 'designation', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'designation/delete/*', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'designation/create', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'designation/*', array('Administrator'), Redirect::to('/') );
Entrust::routeNeedsRole( 'designation/*/edit', array('Administrator'), Redirect::to('/') );

Entrust::routeNeedsRole( 'designation/*/members', array('Administrator'), Redirect::to('/') );

// Filter for Task

Entrust::routeNeedsRole( 'task/active', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'task/overdue', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );


Entrust::routeNeedsRole( 'task/new', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );
Entrust::routeNeedsRole( 'task/*', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );


// Filter for Summary
Entrust::routeNeedsRole( 'summary', array('Administrator','Procurement Personnel'), Redirect::to('/'), false );