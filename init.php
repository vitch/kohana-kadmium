<?php defined('SYSPATH') or die('No direct script access.');

// Static file serving (CSS, JS, images)
Route::set('kadmium/media', 'kadmium/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'kadmium_media',
		'action'     => 'index',
		'file'       => NULL,
	));

$kadmium_config = Kohana::config('kadmium');
$base_path = $kadmium_config->base_path;

// Routes for the kadmium based controllers...
Route::set('kadmium_list', $base_path . '/<controller>/list(/<page>)', array('page'=>'[0-9]+'))
	->defaults(array(
		'directory'  => $base_path,
		'action'     => 'list',
		'page'       => 1
	));

Route::set(
		'kadmium_child_edit',
		$base_path . '/<controller>/edit/<parent_id>/<action>/<id>',
		array(
			'controller' => $kadmium_config->valid_parent_controllers,
			'parent_id'  => '[0-9]+',
			'action'     => $kadmium_config->valid_child_controllers,
			'id'         => '[0-9]+'
		)
	)
	->defaults(array(
		'directory'  => $base_path
	));

Route::set('kadmium', $base_path . '(/<controller>(/<action>(/<id>)))', array('id'=>'[0-9]+'))
	->defaults(array(
		'directory'  => $base_path,
		'controller' => $base_path,
		'action'     => 'index',
	));
