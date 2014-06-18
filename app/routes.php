
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

Route::get('/', 'AuthController@showLogin');

Route::get('user/view',                    'UserController@viewuser');

// Confide routes
Route::get( 'user/create',                 'UserController@create');
Route::post('user',                        'UserController@store');
Route::get( 'login',                  'UserController@login');
Route::post('login',                  'UserController@do_login');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::get( 'logout',                 'UserController@logout');

Route::get('/', function()
{
	return Redirect::to('login');
});

Route::get('/dashboard', 'UserController@dashboard');

<<<<<<< HEAD
Route::patch('user/edit/{id}',['as'=>'user.update', 'uses' => 'UserController@edit'])->before('auth');


//Office routes
Route::resource('offices', 'OfficeController');
Route::get('/offices', 'OfficeController@index');
=======
Route::get( 'user/edit/{id}', function($id)
{
	return View::make('user_edit')->with('id',$id);
});

Route::post('user/edit/{id}',['as'=>'user.update', 'uses' => 'UserController@edit']);

Route::get('create_roles','UserController@getRole');

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

>>>>>>> d316789d5f0c14ab6477fa6c727abc9f8a17c484
