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
		$feedback_message = '';
		
		if (Arr::get($_POST, 'my-action') == 'login') {
			if ($this->auth->login(Arr::get($_POST, 'username'), Arr::get($_POST, 'password'))) {
				$this->on_logged_in(Arr::get($_POST, 'next'));
			}
			$feedback_message = "Sorry - could not log you in with that username and password.";
		} else if ($this->auth->logged_in()) {
			$this->on_logged_in();
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
		$this->template->is_logged_in = false;
		$this->init_template(
			'Logged out!'
		);
		$this->template->content = View::factory(
			'kadmium/user/logged_out',
			array(
				'admin_url' => Kohana::config('kadmium')->base_path
			)
		);
	}

	public function action_edit()
	{
		$this->require_login();

		$this->init_template('Update profile');

		$model = $this->auth->get_user();
		$model = Jelly::query('kadmium_user', $model->id())->select();

		$this->show_edit_page_from_model('Profile', $model, false);
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

	// Hack so that we can ignore it when the password isn't updated...
	// FIXME: Can this be done much nicer with the new validation system?
	protected function include_field($field, $hide_has_many_uniquely = false)
	{
		if ($field instanceof Jelly_Field_Password && $hide_has_many_uniquely) {
			if (Arr::get($_POST, 'field-password') == '' && Arr::get($_POST, 'field-password_confirm') == '' ) {
				return false;
			}
		}
		return parent::include_field($field, $hide_has_many_uniquely);
	}
	
	private function on_logged_in($next_page = '/')
	{
		$this->request->redirect($next_page);
	}

}
