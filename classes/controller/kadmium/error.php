<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kadmium_Error extends Controller_Kadmium
{
	protected $login_required = FALSE;

	public function action_page_not_found()
	{
		$this->init_template('Page not found');
		$this->template->content = View::factory('kadmium/error/page_not_found');
	}
	
	public function action_insufficient_permissions()
	{
		$this->init_template('Insufficient permissions');
		$this->template->content = View::factory('kadmium/error/insufficient_permissions');
	}
	
	public function action_server_error()
	{
		$this->init_template('Server error');
		$this->template->content = View::factory('kadmium/error/server_error');
	}

}
