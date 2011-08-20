<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Kadmium defaults
	'kadmium' => array(
		'current_page'   => array('source' => 'route', 'key' => 'page'),
		'total_items'    => 0,
		'items_per_page' => Kohana::config('kadmium')->results_per_list_page,
		'view'           => 'kadmium/element/pagination',
		'auto_hide'      => TRUE,
	),

);
