<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kadmium_Auth extends Controller_Template
{
	protected $auth;
	protected $login_required = TRUE;

	public function before()
	{
		parent::before();
		$this->auth = Auth::instance();
		if ($this->login_required) {
			$this->require_login();
		}
	}

	protected function require_login()
	{
		if($this->auth->logged_in() == 0) {
			throw new Exception_NoPermission();
		}
	}
}
