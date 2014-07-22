
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

Route::get('/', function()
{
	return Redirect::to('login');
});

//---------- Login Routes
Route::get( 'login', 'UserController@login');
Route::get( 'logout', 'UserController@logout');
Route::post('login', 'UserController@do_login');


//---------- User CRUD Routes
Route::get('user/view', 'UserController@viewUser');
Route::post('user/edit/{id}',[ 'uses' => 'UserController@edit']);
Route::get('user/edit/{id}',[ 'uses' => 'UserController@edit_view']);
Route::post('user/editprof/{id}',[ 'uses' => 'UserController@editprof']);
Route::get('user/editprof/{id}',[ 'uses' => 'UserController@editprof_view']);
Route::get( 'user/create', 'UserController@create');
Route::post('user', 'UserController@store');
Route::post( 'user/delete', 'UserController@disable');
Route::post( 'user/activate', 'UserController@activate');

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
Route::get('/summary', 'PurchaseRequestController@viewSummary');
Route::get('/summary/store', 'PurchaseRequestController@getDateRange');
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

//Checklist Rowtype Routes
Route::post('checklistedit', ['uses' => 'PurchaseRequestController@checklistedit']);
Route::post('certification', ['uses' => 'PurchaseRequestController@certification']);
Route::post('posting', ['uses' => 'PurchaseRequestController@posting']);
Route::post('supplier', ['uses' => 'PurchaseRequestController@supplier']);
Route::post('cheque', ['uses' => 'PurchaseRequestController@cheque']);
Route::post('published', ['uses' => 'PurchaseRequestController@published']);
Route::post('documents', ['uses' => 'PurchaseRequestController@documents']);
Route::post('evaluations', ['uses' => 'PurchaseRequestController@evaluations']);
Route::post('conference', ['uses' => 'PurchaseRequestController@conference']);
Route::post('contractmeeting', ['uses' => 'PurchaseRequestController@contractmeeting']);
Route::post('rfq', ['uses' => 'PurchaseRequestController@rfq']);
Route::post('dateby', ['uses' => 'PurchaseRequestController@dateby']);
Route::post('datebyremark', ['uses' => 'PurchaseRequestController@datebyremark']);
//End Checklist Rowtype Routes


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


//---------- Workflow Routes
Route::get('workflow', function(){
	return View::make('workflows.workflowdash');
});


//---------- Roles Create Routes (Disabled)
	//Route::get('create_roles','UserController@getRole');
Route::get('task/new', 'TaskController@newTask');
Route::post('deladdtask', 'TaskController@deladdtask');
Route::post('addtask', [ 'uses' => 'TaskController@addtask']);
Route::post('task/new', ['as' => 'accept_task', 'uses' => 'TaskController@assignTask']);
Route::post('remarks', 'TaskController@remarks');
Route::post('done', 'TaskController@done');
Route::post('taskimage', 'TaskController@taskimage');	
Route::get('task/active', 'TaskController@active');
Route::get('task/overdue', 'TaskController@overdue');
Route::get('task/{id}', [ 'uses' => 'TaskController@taskpagecall']);


//---------- Image Module Components
Route::post('newcreate', ['uses' => 'purchaseRequestController@create_submit']);
Route::post('newedit', ['uses' => 'purchaseRequestController@edit_submit']);
Route::post('addimage', ['uses' => 'purchaseRequestController@addimage']);
Route::post('delimage', ['uses'=> 'purchaseRequestController@delimage']);


//---------- AJAX Routes

Route::post('workflow/submit/{id}', 'AjaxController@workflowSubmit');

Route::post('workflow/replace/{id}', function($id)
{
	$desc = Task::find($id);
	$data = array(
		"html" => "<div id='description_body'>  $desc->description </h6> </p></div>"
	);
	return Response::json($data);
}); 

Route::post('summary/changeDate', 'AjaxController@SummarySubmit');

/*Route::post('summary/changeDate', function()
{
	$start = Input::get('start');
		$end = Input::get('end');

		$prCount = Reports::whereBetween('pRequestDateReceived', array($start, $end))->count(); 
		$POCount  = Reports::whereBetween('pOrderDateReceived', array($start, $end))->count(); 
		$chequeCount = Reports::whereBetween('chequeDateReceived', array($start, $end))->count(); 

		$data = array(
		"fragments" => array(
			"#PR" => 
			"<div class='well' style='' id='PR'>
				aw
			</div>"),
		);	

		return Response::json($data);
});*/