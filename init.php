<?php defined('SYSPATH') or die('No direct script access.');

// Static file serving (CSS, JS, images)
Route::set('kadmium/media', 'kadmium/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'kadmium_media',
		'action'     => 'index',
		'file'       => NULL,
	));

