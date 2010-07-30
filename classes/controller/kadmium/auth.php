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
		$this->template->is_logged_in = $this->auth->logged_in();
	}

	protected function require_login()
	{
		if($this->auth->logged_in() == 0) {
			throw new Kadmium_Exception_NoPermission();
		}
	}
}
