<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Core_Kadmium extends Controller_Kadmium_Base
{

	public $template = 'kadmium/template';

	protected $after_edit_form_content;
	protected $styles = array();
	protected $scripts = array();

	public function before()
	{
		parent::before();

		$media = Route::get('kadmium/media');

		$this->styles = $this->styles + array(
//			'http://yui.yahooapis.com/2.8.1/build/reset-fonts/reset-fonts.css'  => 'all',
			$media->uri(array('file' => 'css/reset-fonts.css'))  => 'all',
//			'http://yui.yahooapis.com/2.8.1/build/base/base-min.css' => 'all',
			$media->uri(array('file' => 'css/base-min.css'))  => 'all',
			$media->uri(array('file' => 'css/datePicker.css'))  => 'all',
			$media->uri(array('file' => 'css/jquery.asmselect.css'))  => 'all',
			$media->uri(array('file' => 'colorbox/styles/colorbox.css'))  => 'all',
			$media->uri(array('file' => 'css/kadmium.css'))  => 'all',
		);

		$this->scripts = $this->scripts + array(
//			'http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js',
			//$media->uri(array('file' => 'js/jquery-1.4.4.min.js')),
			$media->uri(array('file' => 'js/date.js')),
			$media->uri(array('file' => 'js/jquery.datePicker.js')),
			$media->uri(array('file' => 'tiny_mce/jquery.tinymce.js')),
			$media->uri(array('file' => 'js/jquery.asmselect.js')),
			$media->uri(array('file' => 'colorbox/scripts/jquery.colorbox-min.js')),
			$media->uri(array('file' => 'js/jquery-ui-1.8.6.custom.min.js')), // For it's drag and drop...
			$media->uri(array('file' => 'js/wysiwyg.js')),
			$media->uri(array('file' => 'js/kadmium.js')),
		);

		$this->template->show_profiler = false;
	}

	public function after()
	{

		$this->template->styles = $this->styles;
		$this->template->scripts = $this->scripts;
		$kadmium_config = Kohana::config('kadmium');
		$this->template->navigation_controllers = $kadmium_config->navigation_controllers;

		if (isset($kadmium_config->navigation_controllers_by_role) && $this->auth->logged_in()) {
			foreach ($kadmium_config->navigation_controllers_by_role as $role => $controllers) {
				if ($this->auth->logged_in($role)) {
					$this->template->navigation_controllers += $controllers;
				}
			}
		}
		
		return parent::after();
	}

	protected function init_template($html_title)
	{
		$this->template->html_title = $html_title;
	}

	protected function show_edit_page($item_type, $model_name, $id = 0)
	{
		$this->show_edit_page_from_model(
			$item_type,
			$this->get_model($model_name, $id),
			$id == 0
		);
	}

	// Get's a field from its ID, whether it is edit_inline or not
	private function get_field_by_id($field_ids, $model)
	{
		$field_ids = explode('-', $field_ids);
		array_shift($field_ids); // lose the "field" from the start.
		$fields = array();
		switch (count($field_ids)) {
			case 1:
				$field = $model->meta()->field($field_ids[0]);
				$this->generate_field($model, $fields, $field_ids[0], $field);
				break;
			case 2:
				$sub_model = $model->{$field_ids[0]};
				$field = $sub_model->meta()->field($field_ids[1]);
				$this->generate_field($sub_model, $fields, $field_ids[1], $field, array(), array(), 'field-' . $field_ids[0] . '-');
				break;
			default:
				throw new Exception('NotImplemented - dealing with nested edit_inline HasManyUniquely fields');
		}
		return array($field, $fields);

	}

	protected function show_edit_page_from_model($item_type, $model, $is_new, $extra_redirect_params = array())
	{
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Kadmium', __FUNCTION__);
		}
		if ($this->request->is_ajax()) {
			switch(Arr::get($_POST, 'action', Arr::get($_GET, 'action'))) {
				case 'reload':
					list($field, $fields) = $this->get_field_by_id(Arr::get($_GET, 'field'), $model);
					echo View::factory(
						'kadmium/fields',
						array(
							'fields' => $fields
						)
					);
					$this->auto_render = false;
					return false;
				case 'sortItems':
					list($field, $fields) = $this->get_field_by_id(Arr::get($_POST, 'child_id'), $model);
					$ids = explode(',', Arr::get($_POST, 'ids', ''));
					$index = 1;
					$foreign_model = $field->foreign['model'];
					$sort_on_field = $field->sort_on;
					foreach ($ids as $id) {
						Jelly::query($foreign_model, $id)->select()->set(
							array(
								$sort_on_field => $index++,
							)
						)->save();
					}
					$this->auto_render = false;
					echo '{complete:1}';
					return false;
			}
		}

		// TODO: Check model->loaded() here?
		$title = $this->get_page_heading($item_type, $is_new);
		$this->init_template($title);
		$meta = Jelly::meta($model);

		$feedback_message = '';
		$error_message = '';
		$validation_errors = array();

		if (Arr::get($_POST, 'my-action') == $this->get_save_button_label($item_type, $is_new)) {
			// IsPostBack

			$update_result = $this->update_model_from_post($meta, $model);
			if ($update_result) {
				$validation_errors += $update_result;
			}

			if (count($validation_errors) > 0) {
				$error_message = '<p>There ' . (count($validation_errors) > 1 ? 'were errors' : 'was an error') . ' saving your ' . strtolower($item_type) . '. Please see below for more information.</p>';
			} else {
				// Allow a method to hook into the point where the save has completed
				if (method_exists($this, $this->request->action() . '_save_complete')) {
					call_user_func(array($this, $this->request->action() . '_save_complete'), $model, $is_new);
				}
				if ($is_new) {
					Session::instance()->set('__FLASH__', '<p>Your ' . strtolower($item_type) . ' was successfully created.</p>');
					$edit_url = $this->request->route()->uri(
						$extra_redirect_params +
						array(
							'action' => 'edit',
							'controller' => $this->request->controller(),
							'id' => $model->id(),
						)
					);
					if (Arr::get($_GET, 'lb') == 'true') {
						$edit_url .= '?lb=true';
					}
					$this->on_new_model_generated($model);
					$this->request->redirect($edit_url);
				} else {
					$feedback_message = '<p>Your ' . strtolower($item_type) . ' was successfully updated.</p>';
				}
			}
		}

		if (Session::instance()->get('__FLASH__') != null) {
			$feedback_message = Session::instance()->get('__FLASH__');
			Session::instance()->delete('__FLASH__');
		}

		$this->template->content = View::factory(
			'kadmium/edit',
			array(
				'page_title' => $title,
				'save_button_label' => $this->get_save_button_label($item_type, $is_new),
				'item' => $model,
				'feedback_message' => $feedback_message,
				'error_message' => $error_message,
				'delete_link' => $this->get_delete_link($is_new, $model, $item_type),
				'fields' => View::factory(
					'kadmium/fields',
					array(
						'fields' => $this->generate_fields($model, $meta, 'field-', $validation_errors),
					)
				),
				'show_submit' => $this->should_show_submit($model, $meta),
				'after_form_content' => $this->after_edit_form_content,
			)
		);
		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
	}

	protected function update_model_from_post($meta, $model, $prefix='field-')
	{
		$errors = array();
		foreach ($meta->fields() as $field_id => $field) {
			if (!$this->include_field($field, TRUE)) {
				continue;
			}
			if ($field->prevent_edit) {
				continue;
			}
			if ($field instanceof Jelly_Field_File) {
				if ($_FILES[$prefix . $field_id]['tmp_name'] != '' && $_FILES[$prefix . $field_id]['size'] != 0) {
					$model->set(array($field_id => Arr::get($_FILES, $prefix . $field_id)));
				}
			} else if($field instanceof Jelly_Field_BelongsTo && $field->edit_inline) {
				$sub_model = $model->{$field_id};
				$sub_meta = Jelly::meta($sub_model);
				$sub_prefix = 'field-' . $field_id . '-';
				$sub_errors = $this->update_model_from_post($sub_meta, $sub_model, $sub_prefix);
				if ($sub_errors) {
					$errors += $sub_errors;
				}
			} else {
				$model->set(array($field_id => Arr::get($_POST, $prefix . $field_id)));
			}
		}
		try {
			$model->save();
			if (count($errors)) {
				return $errors;
			}
		} catch (Jelly_Validation_Exception $e) {
			return $e->errors('');
		}
	}

	protected function show_child_model_page($parent_type_name, $child_type_name, $child_model_name)
	{
		$child_id = $this->request->param('id');
		$model = $this->get_model($child_model_name, $child_id);
		$this->show_child_model_page_from_model($parent_type_name, $child_type_name, $model);
	}

	protected function show_child_model_page_from_model($parent_type_name, $child_type_name, $child_model)
	{
		// TODO: Check if id corresponds to a valid item?
		$parent_id = $this->request->param('parent_id');

		if ($this->request->is_ajax() || Arr::get($_GET, 'lb') == 'true') {
			$this->template = View::factory('kadmium/lightbox_template');
			$this->after_edit_form_content = Html::anchor(
				'#',
				'&lt; Back to ' . $parent_type_name,
				array(
					'class' => 'back js-close-link'
				)
			);
		} else {
			if (!isset($this->after_edit_form_content)) {
				$this->after_edit_form_content = Html::anchor(
					Route::get('kadmium')
						->uri(array(
							'controller' => $this->request->controller(),
							'action' => 'edit',
							'id' => $parent_id,
						)
					),
					'&lt; Back to ' . $parent_type_name,
					array(
						'class' => 'back'
					)
				);
			}
		}
		$is_new = $child_model->id() == 0;
		if ($is_new) {
			$child_model->set($child_model->meta()->foreign_key(), $parent_id);
		}
		$this->init_template(($is_new ? 'Add' : 'Update') . ' ' . $child_type_name);
		$this->show_edit_page_from_model(
				$child_type_name,
				$child_model,
				$is_new,
				array(
					'parent_id' => $parent_id,
					'action' => $this->request->action()
				)
			);
	}

	protected function show_list_page($item_type, $model_name, $extra_button_view = '', $view_file = 'kadmium/list')
	{
		// update any sort on fields...
		$fields = Jelly::meta($model_name)->fields();
		$sort_on_field = FALSE;
		foreach($fields as $field) {
			if ($field instanceof Jelly_Field_SortOn) {
				$sort_on_field = $field;
				break;
			}
		}
		if ($sort_on_field && Arr::get($_POST, 'my-action') == 'sortItems') {
			$ids = explode(',', Arr::get($_POST, 'ids', ''));
			$index = 1;
			foreach ($ids as $id) {
				/*
				// Should work but is resetting the is_published field to 0??!
				Jelly::factory($model_name)->set(
					array(
						$sort_on_field => $index++,
					)
				)->save($id);
				*/
				Jelly::query($model_name, $id)->select()->set(
					array(
						$sort_on_field->column => $index++,
					)
				)->save();
			}
			$this->auto_render = false;
			echo '{complete:1}';
			return;
		}

		$this->init_template('List ' . Inflector::plural($item_type));
		$builder = Jelly::query($model_name);
		foreach(Jelly::meta($model_name)->fields() as $field_id => $field) {
			if ($field instanceof Jelly_Field_BelongsTo && $field->show_in_list) {
				$builder->with($field_id);
			}
		}
		$this->modify_list_builder($builder);
		$display_search = $this->_handle_list_search($builder);
		if ($sort_on_field) {
			if (isset($sort_on_field->category_key)) {
				if (is_array($sort_on_field->category_key)) {
					foreach($sort_on_field->category_key as $o) {
						$builder->order_by($o);
					}
				} else {
					$builder->order_by($sort_on_field->category_key);
				}
			}
			$builder->order_by($sort_on_field->column);
			$rpp = 9999999999;
			$allow_sorting = FALSE;
		} else {
			$rpp = Kohana::config('kadmium')->results_per_list_page;
			$allow_sorting = TRUE;
			$chosen_sort = Arr::get($_GET, 's');
			$chosen_dir = Arr::get($_GET, 'd', -1);
			if (!$chosen_sort) {
				$default_sorting = Jelly::meta($model_name)->sorting();
				if (count($default_sorting)) {
					$tmp = array_keys($default_sorting);
					$chosen_sort = $tmp[0];
				}
			}
			if ($chosen_sort) {
				$sorting_field = Jelly::meta($model_name)->field($chosen_sort);
				if ($sorting_field instanceof Jelly_Field_BelongsTo) {
					$chosen_sort = Jelly::meta($model_name)->table()
									. ':'
									. $chosen_sort
									. '.'
									. Jelly::meta($sorting_field->foreign['model'])->name_key();
				}
				$builder->order_by($chosen_sort, $chosen_dir == 1 ? 'asc' : 'desc');
			}
		}

		$count_builder = Jelly::query($model_name);
		$this->modify_list_builder($count_builder);
		$this->_handle_list_search($count_builder);

		// $count = $count_builder->count(); // Doesn't work properly when one of the above user functions has added a group_by clause :(
		$count = $count_builder->select()->count();


		
		$pagination = Pagination::factory(
			array(
				'group' => 'kadmium',
				'total_items' => $count,
				'items_per_page' => $rpp,
				'page' => $this->request->param('page')
			)
		);

		if ($pagination->current_page() != $this->request->param('page')) {
			throw new Kadmium_Exception_PageNotFound();
		}

		$items = $builder->limit($rpp)->offset($pagination->offset())->select();

		$add_link = Route::get('kadmium')->uri(
			array(
				'controller' => $this->request->controller(),
				'action' => 'new'
			)
		);

		$this->template->content = View::factory(
			$view_file,
			array(
				'page_title' => 'List ' . Inflector::plural($item_type),
				'item_type' => $item_type,
				'display_add_links' => !Jelly::factory($model_name)->disable_user_add,
				'add_link' => $add_link,
				'show_edit' => Jelly::factory($model_name)->disable_user_edit !== TRUE,
				'allow_sorting' => $allow_sorting,
				'items' => $items,
				'pagination' => $pagination->render(),
				'extra_button_view' => $extra_button_view,
				'display_search' => $display_search,
			)
		);
	}

	// Allow subclasses to add some extra clauses to the Jelly_Builder for the list pages...
	protected function modify_list_builder(Jelly_Builder $builder)
	{
		return $builder;
	}

	private function _handle_list_search(Jelly_Builder $builder)
	{
		if (method_exists($this, 'handle_list_search')) {
			if ($q = Arr::get($_GET, 'q')) {
				$this->handle_list_search($builder, $q);
			}
			return true;
		}
		return false;
	}

	// Allow subclasses to react when a new model is sucessfully generated (before the redirect to the edit page)...
	protected function on_new_model_generated(Jelly_Model $model)
	{
		// Nothing here but subclasses can implement...
	}

	protected function get_delete_link($is_new, $model, $item_type)
	{
		$delete_link = '';
		if(!$is_new && $model->delete_policy != Kadmium_Core_Model::DELETE_NEVER) {
			$uri_param = $this->request->param('child_action') ? 'child_action' : 'action';

			$delete_uri = $this->request->uri(
				array(
					$uri_param => 'delete',
				)
			);

			if (Arr::get($_GET, 'lb')) {
				$delete_uri .= '?lb=true';
			}

			$delete_link = Html::anchor(
				$delete_uri,
				$this->get_delete_button_label($item_type),
				array(
					'class' => 'delete'
				)
			);
		}
		return $delete_link;
	}

	protected function show_delete_page($item_type, $model_name, $id)
	{
		$model = $this->get_model($model_name, $id);
		if (!$model->loaded()) {
			throw new HTTP_Exception_404();
		}

		if ($this->request->is_ajax() || Arr::get($_GET, 'lb') == 'true') {
			$this->template = View::factory('kadmium/lightbox_template');
		}

		$page_title = 'Delete ' . $item_type;
		$this->init_template($page_title);

		switch ($model->delete_policy) {
			case Kadmium_Core_Model::DELETE_NEVER:
				if ($this->request->param('parent_id')) {
					$uri_params = array(
						'child_action' => 'edit',
					);
				} else {
					$uri_params = array(
						'action' => 'edit',
					);
				}
				$this->template->content = View::factory(
					'kadmium/delete_forbidden',
					array(
						'page_title' => $page_title,
						'item_type' => $item_type,
						'item_name' => $this->_get_item_name($model),
						'edit_link' => Html::anchor(
							$this->request->uri($uri_params),
							'&lt; Back to ' . strtolower($item_type),
							array(
								'class' => 'back'
							)
						)
					)
				);
				break;
			case Kadmium_Core_Model::DELETE_ALL_CHILDREN:
				$this->_show_delete_page($page_title, $item_type, $model);
				break;
			case Kadmium_Core_Model::DELETE_ONLY_SPINSTER:
				list($belongs_to, $children) = $this->get_relations($model);

				if (count($belongs_to) || count($children)) {
					$this->_show_delete_dependancies_page($page_title, $item_type, $model, $belongs_to, $children);
				} else {
					$this->_show_delete_page($page_title, $item_type, $model);
				}
				break;
		}
	}

	private function get_relations(Jelly_Model $model)
	{
		$model_name = Jelly::model_name($model);
		$model_id = $model->id();
		$belongs_to = array();
		$children = array();
		$fields = $model->meta()->fields();
		foreach ($fields as $field) {

			if (isset($field->ignore_for_delete) && $field->ignore_for_delete) {
				continue;
			}

			if ( // FIXME: kinda ugly - need to check if all of these are necessary...
				$field instanceof Jelly_Field_ManyToMany ||
				$field instanceof Jelly_Field_BelongsTo ||
				$field instanceof Jelly_Field_HasMany ||
				$field instanceof Jelly_Field_HasOne
			) {

				$related_model = $field->foreign['model'];
				$related_model_fields = Jelly::meta($related_model)->fields();

				foreach ($related_model_fields as $related_model_field) {
					if ($related_model_field instanceof Jelly_Field_BelongsTo && $related_model_field->foreign['model'] == $model_name) {
						$dependencies = Jelly::query($related_model)->where($related_model_field->name, '=', $model_id)->select();

						if ($field instanceof Jelly_Field_HasManyUniquely) {
							$add_to_array = 'children';
							$link_route = Route::get('kadmium_child_edit');
							$uri_params = array(
								'controller' => $model_name,
								'child_action' => 'edit',
								'action' => $related_model,
								'parent_id' => $model_id
							);
						} else {
							$add_to_array = 'belongs_to';
							$link_route = Route::get('kadmium');
							$uri_params = array(
								'controller' => $related_model,
								'action' => 'edit',
							);
						}

						foreach ($dependencies as $dependency) {
							array_push(
								$$add_to_array,
								array(
									'model' => $related_model,
									'name' => $dependency->name(),
									'link' => $link_route->uri(
										$uri_params + array(
											'id' => $dependency->id(),
										)
									)
								)
							);
						}
					} elseif ($related_model_field instanceof Jelly_Field_ManyToMany && $related_model_field->foreign['model'] == $model_name) {
						$get_links = Jelly::query($related_model_field->through['model'])
										->where($related_model_field->through['fields'][1], '=', $model_id)
										->select();

						foreach ($get_links as $link) {
							$related = $link->{$related_model_field->through['fields'][0]};
							if (!$related->loaded()) {
								// TODO: throw error? Send email to admin? Delete row from link table if the related model doesn't exist?
								// Try to get to the root of the problem and why this happened in the first place?
								echo Debug::vars('Problem loading ' . $related_model . ' ' . $link->get_raw($related_model_field->through['fields'][0]));
							}
							$belongs_to[] = array(
								'model' => $related_model,
								'name' => $related->name(),
								'link' => Route::get('kadmium')->uri(
									array(
										'controller' => $related_model,
										'action' => 'edit',
										'id' => $related->id(),
									)
								)
							);
						}
					}
				}
			}
		}
		return array($belongs_to, $children);
	}

	private function _show_delete_dependancies_page($page_title, $item_type, Jelly_Model $model, array $belongs_to, array $children)
	{
		if ($this->request->param('parent_id')) {
			$uri_params = array(
				'child_action' => 'edit',
			);
		} else {
			$uri_params = array(
				'action' => 'edit',
			);
		}
		$this->template->content = View::factory(
			'kadmium/delete_dependencies',
			array(
				'page_title' => $page_title,
				'item_type' => $item_type,
				'item_name' => $this->_get_item_name($model),
				'belongs_to' => $belongs_to,
				'children' => $children,
				'edit_link' => Html::anchor(
					$this->request->uri($uri_params),
					'&lt; Back to ' . strtolower($item_type),
					array(
						'class' => 'back'
					)
				)
			)
		);
	}

	private function _show_delete_page($page_title, $item_type, Jelly_Model $model)
	{
		$delete_button_label = $this->get_delete_button_label($item_type);
		if (Arr::get($_POST, 'my-action') == $delete_button_label) {
			// IsPostBack
			$name = $this->_get_item_name($model);
			$model->delete();
			$this->template->content = View::factory(
				'kadmium/deleted',
				array(
					'page_title' => $page_title,
					'item_type' => $item_type,
					'item_name' => $name,
				)
			);
		} else {
			$this->template->content = View::factory(
				'kadmium/delete',
				array(
					'page_title' => $page_title,
					'delete_button_label' => $delete_button_label,
					'item_type' => $item_type,
					'item_name' => $this->_get_item_name($model)
				)
			);
		}
	}

	private function _get_item_name(Jelly_Model $model)
	{
		$name = $model->name();
		if ($name instanceof Jelly_Builder) {
			$name = $name->select()->name();
		}
		return $name;
	}


	/**
	 * @return Jelly_Model
	 */
	protected function get_model($model_name, $id = 0)
	{
		if ($id > 0) {
			$model = Jelly::query($model_name, $id)->select();
			if (!$model->loaded()) {
				throw new HTTP_Exception_404();
			}
		} else {
			$model = Jelly::factory($model_name);
		}
		return $model;
	}

	protected function should_show_submit(Jelly_Model $model, Jelly_Meta $meta)
	{
		foreach ($meta->fields() as $field_id => $field) {
			if (!$this->include_field($field, $model->id() == 0)) {
				continue;
			}
			if ($field->prevent_edit) {
				continue;
			}
			if ($field instanceof Jelly_Field_HasManyUniquely) {
				continue;
			}
			return true;
		}
		return false;
	}

	protected function generate_fields(Jelly_Model $model, Jelly_Meta $meta, $field_prefix, array $validation_errors = array())
	{
		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Kadmium', __FUNCTION__);
		}
		$has_autocomplete = FALSE;
		$fields = array();
		foreach ($meta->fields() as $field_id => $field) {
			$this->generate_field($model, $fields, $field_id, $field, $validation_errors, array(), $field_prefix);
			if ($field->uses_autocomplete) {
				$has_autocomplete = TRUE;
			}
		}
		if ($has_autocomplete) {
			$media = Route::get('kadmium/media');
			$this->scripts[] = $media->uri(
				array(
					 'file' => 'js/jquery.autocomplete.min.js'
				)
			);
			$this->scripts[] = $media->uri(
				array(
					 'file' => 'js/kadmium.autocomplete.js?1'
				)
			);
		}
		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
		return $fields;
	}

	protected function generate_field(Jelly_Model $model, & $fields, $field_id, $field, array $validation_errors = array(), $attrs = array(), $field_prefix = 'field-')
	{

		if (Kohana::$profiling === TRUE)
		{
			$benchmark = Profiler::start('Kadmium', __FUNCTION__ /*. '[' . $field_id . ']'*/);
		}
		$field_id_attr = $field_prefix . $field->name;
		if (!$this->include_field($field, $model->id() == 0)) {
			if (isset($benchmark))
			{
				Profiler::stop($benchmark);
			}
			return;
		}

		$id_attribs = array('attributes'=>array('id'=>$field_id_attr) + $attrs, 'name' => $field_id_attr);

		if ($field->prevent_edit) {
			$label = Form::label($field_id_attr, $field->label);
			$field_str = $field->display($model, $model->get($field_id));
			$fields[$label] = '<div class="non-editable">' . ($field_str == '' || $field_str == ' ' ? '&nbsp;' : $field_str) . '</div>';
		} else {

			$field_output = $model->input($field->name, $id_attribs);

			if ($field instanceof Jelly_Field_HasManyUniquely) {
				$label = View::factory(
					'jelly/field/hasmanyuniquely/header',
					array(
						'label' => $field->label,
						'is_sortable' => isset($field->sort_on) && $field->sort_on,
					)
				) . '';
			} else if ($field instanceof Jelly_Field_BelongsTo && $field->edit_inline) {
				$label = '<!-- ' . $field_id . ' -->';
				$sub_model = $model->get($field_id)->select();
				$sub_meta = Jelly::meta($sub_model);
				$field_output = View::factory(
					'kadmium/fieldset_subedit',
					array(
						'field_id' => $field_id,
						'label' => $field->label,
						'fields' => $this->generate_fields($sub_model, $sub_meta, 'field-' . $field_id . '-', $validation_errors),
					)
				);
			} else {
				$label = Form::label($field_id_attr, $field->label);
			}

			if (isset($field->tooltip)) {
				$field_output .= ' <div class="tooltip">?<div>' . $field->tooltip . '</div></div>';
			}

			$fields[$label] = $field_output;

			if (isset($validation_errors[$field_id])) {
				array_push($field->css_class, 'error');
				$fields[$label]->errors = $validation_errors[$field_id];
			}
		}
		if (isset($benchmark))
		{
			Profiler::stop($benchmark);
		}
	}

	protected function include_field($field, $hide_has_many_uniquely = FALSE)
	{
		if ($field instanceof Jelly_Field_Primary) {
			return FALSE;
		}
		if ($field instanceof Jelly_Field_Expression && !$field->prevent_edit) {
			return FALSE;
		}
		if ($field->show_in_edit === FALSE) {
			return FALSE;
		}
		if ($field instanceof Jelly_Field_Timestamp && ($field->auto_now_create || $field->auto_now_update)) {
			return FALSE;
		}
		// We don't update Jelly_Field_HasManyUniquely fields but we do need to include them when we are layout out the form (as they appear as a series of links to actually update the fields).
		if ($hide_has_many_uniquely && $field instanceof Jelly_Field_HasManyUniquely) {
			return FALSE;
		}
		return TRUE;
	}

	protected function get_save_button_label($item_type, $is_new)
	{
		return $this->get_page_heading($item_type, $is_new);
	}

	protected function get_delete_button_label($item_type)
	{
		return 'Delete ' . $item_type;
	}

	protected function get_page_heading($item_type, $is_new)
	{
		return ($is_new ? 'Add' : 'Update') . ' ' . $item_type;
	}

}
