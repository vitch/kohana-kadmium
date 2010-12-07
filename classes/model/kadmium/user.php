<?php defined('SYSPATH') or die ('No direct script access.');

class Model_Kadmium_User extends Model_Auth_User
{

	public $delete_policy = Kadmium_Model_Core::DELETE_NEVER;

	public static function initialize(Jelly_Meta $meta)
    {
		Model_Auth_User::initialize($meta);

		$meta->fields('username')->label = 'Username';
		$meta->fields('username')->prevent_edit = true;
		$meta->fields('password')->label = 'Password (leave blank for no change)';
		$meta->fields('password_confirm')->label = 'Confirm password';
		$meta->fields('password')->rules = $meta->fields('password_confirm')->rules = array(
			'max_length' => array(50),
			'min_length' => array(6)
		);
		$meta->fields('email')->label = 'Email address';
		$meta->fields('email')->rules = array(
			'not_empty' => array(TRUE),
			'max_length' => array(127)
		);

		// Hide all of these fields from editing...
		$meta->fields('logins')->show_in_edit = FALSE;
		$meta->fields('last_login')->show_in_edit = FALSE;
		$meta->fields('tokens')->show_in_edit = FALSE;
		$meta->fields('roles')->show_in_edit = FALSE;
	}
}