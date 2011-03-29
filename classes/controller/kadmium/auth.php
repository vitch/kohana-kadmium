<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kadmium_Auth extends Controller_Template
{
	/**
	 * @var Auth $auth
	 */
	protected $auth;
	protected $login_required = TRUE;
	protected $role_required = FALSE;

	public function before()
	{
		parent::before();
		$this->auth = Auth::instance();
		if ($this->login_required) {
			$this->require_login();
		}
		$this->template->is_logged_in = $this->auth->logged_in();
		if ($this->role_required) {
			$this->require_role($this->role_required);
		}
		if ($this->template->is_logged_in) {
			$this->template->username = $this->auth->get_user()->username;
		}
	}

	protected function require_login()
	{
		if($this->auth->logged_in() == 0) {
			throw new Kadmium_Exception_NoPermission();
		}
	}

	protected function require_role($roles)
	{
		if ($this->has_role($roles)) {
			return true;
		}
		throw new Kadmium_Exception_NoPermission();
	}

	protected function has_role($roles)
	{
		if (!$this->template->is_logged_in) {
			return false;
		}
		if (!is_array($roles)) {
			$roles = array($roles);
		}
		foreach($roles as $role) {
			if ($this->auth->get_user()->has_role($role)) {
				return true;
			}
		}
		return false;
	}
}
