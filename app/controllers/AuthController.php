<?php

class AuthController extends BaseController {
	public function showLogin()
    {
        // Show the login page
        return View::make('login');
    }
}