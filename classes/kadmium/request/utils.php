<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Request_Utils
{
	static function uri(Request $request, array $params = array())
	{
		return $request->route()->uri(
			$params +
			$request->param() +
			array(
				'directory' => $request->directory(),
				'controller' => $request->controller(),
				'action' => $request->action(),
			)
		);
	}
}
