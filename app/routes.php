
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

/*
	CODE REVIEW:
		- put functions to controller
		- use 'as' when necessary
		- try removing unnecessary routes made by confide
		- remove spaces
		- remove unused routes
*/
Route::get('/', function()
{
	return Redirect::to('login');
});

//---------- Login Routes
Route::get( 'login',                  'UserController@login');
Route::get( 'logout',                 'UserController@logout');
Route::post('login',                  'UserController@do_login');


//CODE REVIEW: try removing unnecessary routes made by confide
//---------- User CRUD Routes
Route::get('user/view',                    'UserController@viewUser');
Route::get( 'user/create',                 'UserController@create');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::post('user/edit/{id}',[ 'uses' => 'UserController@edit']);
Route::post('user/editprof/{id}',[ 'uses' => 'UserController@editprof']);
Route::post('user',                        'UserController@store');
Route::get( 'user/edit/{id}', function($id)
{
	return View::make('useredit')->with('id',$id);
});
Route::get( 'user/editprof/{id}', function($id)
{
	return View::make('user.editprofile')->with('id',$id);
});
Route::post( 'user/delete', function()
{
	$errors="Account Deactivated.";
	$id=Input::get('hide');

	DB::table('users')->where('id', $id)->update(array('confirmed' => 0));
	
	Session::flash('message','Successfully deleted the user.');
	return Redirect::to('user/view');
});

Route::post( 'user/activate', function()
{
	$errors="Account Activated.";
	$id=Input::get('hide');

	DB::table('users')->where('id', $id)->update(array('confirmed' => 1));
	
	//Session::flash('message','Successfully activated the user.');
	return Redirect::to('user/view');
});

//---------- Dashboard Routes
Route::get('/dashboard', 'UserController@dashboard');


//---------- Office routes
Route::resource('offices', 'OfficeController');
Route::get('offices', 'OfficeController@index');
Route::post('offices/delete/{id}',['as' => 'offices.delete', 'uses' => 'OfficeController@deleteOffice']);
Route::post('offices/{id}/edit',['as' => 'offices.update', 'uses' => 'OfficeController@update']);

//---------- Purchase Request Routes
Route::get('purchaseRequests','PurchaseRequestController@viewAll');
Route::get('purchaseRequest/view','PurchaseRequestController@view');
Route::get('purchaseRequest/create', 'PurchaseRequestController@create');
Route::get('purchaseRequest/edit','PurchaseRequestController@edit');
Route::get( 'purchaseRequest/vieweach/{id}', 'PurchaseRequestController@vieweach');
Route::get( 'purchaseRequest/closed', 'PurchaseRequestController@viewClosed');
Route::get( 'purchaseRequest/overdue', 'PurchaseRequestController@viewOverdue');
Route::get( 'purchaseRequest/cancelled', 'PurchaseRequestController@viewCancelled');
Route::post('purchaseRequest/edit/{id}',[ 'as' => 'purchaseRequest_editsubmit', 'uses' => 'PurchaseRequestController@edit_submit']);
Route::post('purchaseRequest/create', ['as' => 'purchaseRequest_submit', 'uses' => 'PurchaseRequestController@create_submit']);
Route::post('purchaseRequest/changeForm/{id}', function($id)
{

		$data = array(
		"html" => 
			"<div id='pr_form'>
				<form action='submitForm/$id' id='form' method='post'>
					<input type='hidden' id='hide_reason' name='hide_reason'>
				</form>
			</div>"
		);

	return Response::json($data);
});

Route::post('purchaseRequest/submitForm/{id}', ['as' => 'submitForm', 'uses' => 'PurchaseRequestController@changeForm']);


Route::post('checklistedit', ['uses' => 'PurchaseRequestController@checklistedit']);
Route::post('insertaddon', ['uses' => 'PurchaseRequestController@insertaddon']);
Route::post('editaddon', ['uses' => 'PurchaseRequestController@editaddon']);
Route::get( 'purchaseRequest/edit/{id}', ['uses'=>'PurchaseRequestController@editpagecall']);


//---------- Designation Routes
Route::resource('designation', 'DesignationController');

Route::get('designation', 'DesignationController@index');
Route::post('designation/delete/{id}',['as' => 'designation.delete', 'uses' => 'DesignationController@deleteDesignation']);
Route::post('designation/{id}/edit',['as' => 'desingation.update', 'uses' => 'DesignationController@update']);

Route::get('designation/{id}/members', ['as'=>'designation_members', 'uses' => 'DesignationController@members']);
Route::post('designation/assign',['as'=>'designation.assign', 'uses' => 'DesignationController@assign']);

Route::post('designation/{id}/members', ['as'=>'designation_members_save', 'uses' => 'DesignationController@save_members']);

//CODE REVIEW: remove unused routes
//---------- Workflow Routes
Route::get('workflow/below-fifty', function(){
	return View::make('workflows.below_fifty_workflow');
});
Route::get('workflow/belowFifty', function(){
	return View::make('workflows.below_fifty');
});
Route::get('workflow/aboveFifty', function(){
	return View::make('workflows.above_fifty');
});
Route::get('workflow/aboveFive', function(){
	return View::make('workflows.above_five');
});
Route::get('workflow', function(){
	return View::make('workflows.workflowdash');
});
Route::get('workflowsample', function(){
	return View::make('workflows.below_fifty_workflow');
});

Route::post('workflow/replace/{id}', function($id)
{
	//$user = User::find($id);
	$desc = Task::find($id);
	//{{ $fullname = $user->lastname . ", " . $user->firstname; }}
	// If you had a database you could easily fetch the content from the database here...

	$data = array(
		"html" => "<div id='description_body'>  $desc->description </h6> </p></div>"
	);
	
	return Response::json($data);
});


Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});


//---------- Roles Create Routes (Disabled)
	//Route::get('create_roles','UserController@getRole');
Route::get('task/new', 'TaskController@newTask');
Route::post('deladdtask', 'TaskController@deladdtask');
Route::post('addtask', [ 'uses' => 'TaskController@addtask']);
Route::post('task/new', ['as' => 'accept_task', 'uses' => 'TaskController@assignTask']);
Route::post('remarks', 'TaskController@remarks');
Route::post('done', 'TaskController@done');
Route::get('task/active', 'TaskController@active');
Route::get('task/overdue', 'TaskController@overdue');
Route::get('task/{id}', [ 'uses' => 'TaskController@taskpagecall']);
	

//---------- AJAX Routes
Route::post('workflow/submit/{id}', function()
{
	// When the form is submitted, we can do some DB queries and let the user know that the form was submitted.

	//$name = e(Input::get('task_id'));
	$designation = e(Input::get('designa'));

	//$id_drop= Input::get('task_id');
	if($designation == 0)
	{
		$des_name = "";
	}
	else
	{
		$des = Designation::find($designation);
		$des_name = e($des->designation);
	}

	$id = Input::get('task_id');
	$assignd = Task::find($id);
	$assignd->designation_id = Input::get('designa');
	$assignd->save();


	
	if($designation == 0)
	{
		$data = array(
			"html" => "<div id='insert_$id' class='mode1'> None  </div>"
		);
	}
	else
	{
		$data = array(
			"html" => "<div id='insert_$id' class='mode1'> $des_name  </div>"
		);
	}

	return Response::json($data);
});


//---------- Image Module Components
Route::post('newcreate', ['uses' => 'purchaseRequestController@create_submit']);
Route::post('newedit', ['uses' => 'purchaseRequestController@edit_submit']);
Route::post('addimage', ['uses' => 'purchaseRequestController@addimage']);
Route::post('delimage', ['uses'=> 'purchaseRequestController@delimage']);


