<?php defined('SYSPATH') or die('No direct script access.');

// Static file serving (CSS, JS, images)
Route::set('kadmium/media', 'kadmium/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'kadmium_media',
		'action'     => 'index',
		'file'       => NULL,
	));

$kadmium_config = Kohana::$config->load('kadmium');
$base_path = $kadmium_config->base_path;

// Routes for the kadmium based controllers...
Route::set('kadmium_list', $base_path . '/<controller>/<action>(/<page>)', array('page'=>'[0-9]+', 'action'=>'list|csv'))
	->defaults(array(
		'directory'  => $base_path,
		'action'     => 'list',
		'page'       => 1
	));

Route::set(
		'kadmium_child_edit',
		$base_path . '/<controller>/<child_action>/<parent_id>/<action>/<id>',
		array(
			'controller'   => $kadmium_config->valid_parent_controllers,
			'parent_id'    => '[0-9]+',
			'action'       => $kadmium_config->valid_child_controllers,
			'child_action' => 'edit|delete',
			'id'           => '[0-9]+'
		)
	)
	->defaults(array(
		'directory'    => $base_path,
		'child_action' => 'edit'
	));

Route::set('kadmium_error', $base_path . '/error/<action>')
	->defaults(array(
		'directory'  => 'kadmium',
		'controller' => 'error',
		'action'     => 'index',
	));

Route::set('kadmium', $base_path . '(/<controller>(/<action>(/<id>)))', array('id'=>'[0-9]+'))
	->defaults(array(
		'directory'  => $base_path,
		'controller' => $base_path,
		'action'     => 'index',
	));

