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
		$this->template->project_name = Kohana::config('kadmium')->site_name;
		$this->template->is_logged_in = $this->auth->logged_in();
		if ($this->role_required) {
			$this->require_role($this->role_required);
		}
		$this->template->user_name = $this->template->is_logged_in ? $this->auth->get_user()->username : '';
	}

	public function after()
	{
		if ($this->auth->logged_in()) {
			$this->response->headers('Cache-control', 'private');
		}

		return parent::after();
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
		if(is_array($roles)) {
			foreach($roles as $role) {
				if ($this->auth->logged_in($role)) {
					return TRUE;
				}
			}
			return FALSE;
		}
		return $this->auth->logged_in($roles);
	}
}
