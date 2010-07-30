<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Kadmium_User extends Controller_Kadmium
{
	protected $login_required = FALSE;

	public function before()
	{
		$this->section = 'user';
		parent::before();
	}

	public function action_login()
	{
		if ($this->auth->logged_in()) {
			$this->on_logged_in();
		}
		
		$feedback_message = '';
		
		if (Arr::get($_POST, 'my-action') == 'login') {
			if ($this->auth->login(Arr::get($_POST, 'username'), Arr::get($_POST, 'password'))) {
				$this->on_logged_in(Arr::get($_POST, 'next'));
			}
			$feedback_message = "Sorry - could not log you in with that username and password.";
		}
		
		$this->init_template(
			'Login'
		);
		$this->template->content = View::factory(
			'kadmium/user/login',
			array(
				'username' => Arr::get($_POST, 'username'),
				'feedback_message' => $feedback_message
			)
		);
	}

	public function action_logout()
	{ 
		if ($this->auth->logged_in())
		{
			$this->auth->logout();
		}
		$this->init_template(
			'Logged out!'
		);
		$this->template->content = View::factory(
			'kadmium/user/logged_out'
		);
	}

	public function action_loggedin()
	{
		$this->require_login();
		$this->init_template(
			'You are now logged in!'
		);
		$this->template->content = View::factory(
			'kadmium/user/logged_in',
			array(
				'user' => Auth::instance()->get_user()->username,
			)
		);
	}

	public function action_insufficient_permissions()
	{
		$this->init_template(
			'Not allowed!'
		);
		$this->template->content = View::factory(
			'kadmium/error/insufficient_permissions'
		);
	}
	
	private function on_logged_in($next_page = '/')
	{
		$this->request->redirect($next_page);
	}

}
