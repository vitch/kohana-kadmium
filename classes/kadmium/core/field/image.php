<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_Image extends Jelly_Core_Field_Image
{

	public $show_in_list = FALSE;

	public $web_path = '';

	public $delete_file = TRUE;

	public function display($model, $value)
	{
		$path = count($this->thumbnails) ? $this->thumbnails[0]['path'] : $this->path;
		return $value == '' ? $value : Html::image($this->get_web_path($path).$value);
	}

	public function get_web_path($path, $thumbnail_index = -1)
	{
		if ($thumbnail_index == -1 || !isset($this->thumbnails[$thumbnail_index]['web_path'])) {
			return $this->web_path ? $this->web_path : str_replace(DOCROOT, '', $path);
		}
		return $this->thumbnails[$thumbnail_index]['web_path'];
	}

	protected function _transform(Image $image, array $transformations)
	{
		// Process tranformations
		foreach ($transformations as $transformation => $params)
		{
			if ($transformation !== 'factory' OR $transformation !== 'save' OR $transformation !== 'render')
			{
				if ($transformation == 'resize') {
					if ($image->width < $params[0] && $image->height < $params[1]) {
						continue;
					}
				}
				// Call the method excluding the factory, save and render methods
				call_user_func_array(array($image, $transformation), $params);
			}
		}

		return $image;
	}

}