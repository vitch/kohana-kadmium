<?php defined('SYSPATH') or die ('No direct script access.');

class Model_Kadmium_User extends Model_Auth_User
{

	public $delete_policy = Kadmium_Core_Model::DELETE_NEVER;

	public static function initialize(Jelly_Meta $meta)
    {
		Model_Auth_User::initialize($meta);

		$meta->field('username')->label = 'Username';
		$meta->field('username')->prevent_edit = true;
		$meta->field('password')->label = 'Password (leave blank for no change)';
//		$meta->field('password_confirm')->label = 'Confirm password';
//		$meta->field('password')->rules = $meta->fields('password_confirm')->rules = array(
//			'max_length' => array(50),
//			'min_length' => array(6)
//		);
		$meta->field('email')->label = 'Email address';
		$meta->field('email')->rules = array(
			'not_empty' => array(TRUE),
			'max_length' => array(127)
		);

		// Hide all of these fields from editing...
		$meta->field('logins')->show_in_edit = FALSE;
		$meta->field('last_login')->show_in_edit = FALSE;
		$meta->field('user_tokens')->show_in_edit = FALSE;
		$meta->field('roles')->show_in_edit = FALSE;
	}
}