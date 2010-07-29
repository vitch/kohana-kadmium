<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kadmium_Core extends Controller_Kadmium_Base
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
			'http://yui.yahooapis.com/2.6.0/build/reset-fonts/reset-fonts.css'  => 'all',
			//$media->uri(array('file' => 'css/reset-fonts.css'))  => 'all',
			'http://yui.yahooapis.com/2.8.1/build/base/base-min.css' => 'all',
			$media->uri(array('file' => 'css/datePicker.css'))  => 'all',
			$media->uri(array('file' => 'css/jquery.asmselect.css'))  => 'all',
			$media->uri(array('file' => 'colorbox/styles/colorbox.css'))  => 'all',
			$media->uri(array('file' => 'css/kadmium.css'))  => 'all',
		);

		$this->scripts = $this->scripts + array(
			//'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js',
			$media->uri(array('file' => 'js/jquery-1.4.2.min.js')),
			$media->uri(array('file' => 'js/date.js')),
			$media->uri(array('file' => 'js/jquery.datePicker.js')),
			$media->uri(array('file' => 'tiny_mce/jquery.tinymce.js')),
			$media->uri(array('file' => 'js/jquery.asmselect.js')),
			$media->uri(array('file' => 'js/jquery.tablednd_0_5.js')),
			$media->uri(array('file' => 'colorbox/scripts/jquery.colorbox-min.js')),
			$media->uri(array('file' => 'js/jquery-ui-1.8.2.custom.min.js')), // For it's drag and drop...
			$media->uri(array('file' => 'js/kadmium.js')),
		);
	}

	public function after()
	{

		$this->template->styles = $this->styles;
		$this->template->scripts = $this->scripts;
		$this->template->navigation_controllers = Kohana::config('kadmium')->navigation_controllers;
		
		return parent::after();
	}

	protected function show_edit_page($item_type, $model_name, $id = 0)
	{
		$this->show_edit_page_from_model(
			$item_type,
			$this->get_model($model_name, $id),
			$id == 0
		);
	}

	protected function show_edit_page_from_model($item_type, $model, $is_new, $extra_redirect_params = array())
	{
		if (Request::$is_ajax) {
			switch(Arr::get($_POST, 'action', Arr::get($_GET, 'action'))) {
				case 'reload':
					$field_id = Arr::get($_GET, 'field');
					$fields = array();
					$field = $model->meta()->fields($field_id);
					$this->generate_field($model, $fields, $field_id, $field);
					echo View::factory(
						'kadmium/fields',
						array(
							'fields' => $fields
						)
					);
					$this->auto_render = false;
					return false;
				case 'sortItems':
					$field = $model->meta()->fields(Arr::get($_POST, 'child_id'));
					$a = $field->foreign;
					$ids = explode(',', Arr::get($_POST, 'ids', ''));
					$index = 1;
					$foreign_model = $field->foreign['model'];
					$sort_on_field = $field->sort_on;
					foreach ($ids as $id) {
						Jelly::select($foreign_model, $id)->set(
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
		$title = ($is_new ? 'Add' : 'Update') . ' ' . $item_type;
		$this->init_template($title);
		$meta = Jelly::meta($model);

		$feedback_message = '';
		$error_message = '';
		$validation_errors = array();

		if (Arr::get($_POST, 'my-action') == $title) {
			// IsPostBack
			foreach ($meta->fields() as $field_id => $field) {
				if (!$this->include_field($field, TRUE)) {
					continue;
				}
				if ($field instanceof Field_File) {
					if ($_FILES[$field_id]['tmp_name'] != '' && $_FILES[$field_id]['size'] != 0) {
						$model->set(array($field_id => Arr::get($_FILES, $field_id)));
					}
				} else {
					$model->set(array($field_id => Arr::get($_POST, $field_id)));
				}
			}
			try {
				$model->save();
			} catch (Validate_Exception $e) {
				$validation_errors += $e->array->errors('');
			}

			if (count($validation_errors) > 0) {
				$error_message = '<p>There ' . (count($validation_errors) > 1 ? 'were errors' : 'was an error') . ' saving your ' . strtolower($item_type) . '. Please see below for more information.</p>';
			} else {
				if ($is_new) {
					Session::instance()->set('__FLASH__', '<p>Your ' . strtolower($item_type) . ' was successfully created.</p>');
					$edit_url = $this->request->route->uri(
						$extra_redirect_params +
						array(
							'action' => 'edit',
							'controller' => $this->request->controller,
							'id' => $model->id(),
						)
					);
					if (Arr::get($_GET, 'lb') == 'true') {
						$edit_url .= '?lb=true';
					}
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

		$delete_link = '';
		if(!$is_new && $model->delete_policy != Kadmium_Model_Core::DELETE_NEVER) {
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
				'Delete ' . $item_type,
				array(
					'class' => 'delete'
				)
			);
		}

		$this->template->content = View::factory(
			'kadmium/edit',
			array(
				'page_title' => $title,
				'item' => $model,
				'feedback_message' => $feedback_message,
				'error_message' => $error_message,
				'delete_link' => $delete_link,
				'fields' => View::factory(
					'kadmium/fields',
					array(
						'fields' => $this->generate_fields($model, $meta, 'field-', $validation_errors),
					)
				),
				'after_form_content' => $this->after_edit_form_content,
			)
		);
	}

	protected function show_child_model_page($parent_type_name, $child_type_name, $child_model_name)
	{
		// TODO: Check if id corresponds to a valid item?
		$parent_id = $this->request->param('parent_id');

		if (Request::$is_ajax || Arr::get($_GET, 'lb') == 'true') {
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
							'controller' => $this->request->controller,
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
		$child_id = $this->request->param('id');
		$is_new = $child_id == 0;
		$model = $this->get_model($child_model_name, $child_id);
		if ($is_new) {
			$model->set($model->meta()->foreign_key(), $parent_id);
		}
		$this->init_template(($child_id == 0 ? 'Add' : 'Update') . ' ' . $child_type_name);
		$this->show_edit_page_from_model(
				$child_type_name,
				$model,
				$is_new,
				array(
					'parent_id' => $parent_id,
					'action' => $this->request->action
				)
			);
	}

	protected function show_list_page($item_type, $model_name)
	{
		// update any sort on fields...
		$sort_on_field = Jelly::factory($model_name)->sort_field;
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
				Jelly::select($model_name, $id)->set(
					array(
						$sort_on_field => $index++,
					)
				)->save();
			}
			$this->auto_render = false;
			echo '{complete:1}';
			return;
		}

		$this->init_template('List ' . Inflector::plural($item_type));
		$builder = Jelly::select($model_name);
		if ($sort_on_field) {
			$builder->order_by($sort_on_field);
		}
		$rpp = Kohana::config('kadmium')->results_per_list_page;
		$pagination = Pagination::factory(
			array(
				'total_items' => $builder->count(),
				'items_per_page' => $rpp,
			)
		);

		$items = $builder->limit($rpp)->offset($pagination->offset)->execute();

		$add_link = Jelly::factory($model_name)->disable_user_add ?
				'' :
				Html::anchor(
					Route::get('kadmium')
						->uri(array(
							'controller' => $this->request->controller,
							'action' => 'new'
						)),
					'Add new ' . strtolower($item_type),
					array(
						'class' => 'add'
					)
				);

		$this->template->content = View::factory(
			'kadmium/list',
			array(
				'page_title' => 'List ' . Inflector::plural($item_type),
				'item_type' => $item_type,
				'add_link' => $add_link,
				'show_edit' => Jelly::factory($model_name)->disable_user_edit !== TRUE,
				'items' => $items,
				'pagination' => $pagination->render(),
			)
		);
	}

	protected function show_delete_page($item_type, $model_name, $id)
	{
		$model = $this->get_model($model_name, $id);
		if (!$model->loaded()) {
			$this->page_not_found();
		}

		if (Request::$is_ajax || Arr::get($_GET, 'lb') == 'true') {
			$this->template = View::factory('kadmium/lightbox_template');
		}

		$page_title = 'Delete ' . $item_type;
		$this->init_template($page_title);

		switch ($model->delete_policy) {
			case Kadmium_Model_Core::DELETE_NEVER:
				$this->template->content = View::factory(
					'kadmium/delete_forbidden',
					array(
						'page_title' => $page_title,
						'item_type' => $item_type,
						'item_name' => $model->name(),
						'edit_link' => Html::anchor(
											$this->request->route
												->uri(array(
													'controller' => $this->request->controller,
													'action' => 'edit',
													'id' => $model->id()
												)),
											'&lt; Back to ' . strtolower($item_type),
											array(
												'class' => 'back'
											)
										)
					)
				);
				break;
			case Kadmium_Model_Core::DELETE_ALL_CHILDREN:
				$this->_show_delete_page($page_title, $item_type, $model);
				break;
			case Kadmium_Model_Core::DELETE_ONLY_SPINSTER:
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
			if ($field instanceof Jelly_Field_Relationship) { // TODO: Shouldn't Field_Relationship work? But it's not inherited through...
				$related_model = $field->foreign['model'];

				$related_model_fields = Jelly::meta($related_model)->fields();
				foreach ($related_model_fields as $related_model_field) {
					if ($related_model_field instanceof Field_BelongsTo && $related_model_field->foreign['model'] == $model_name) {
						$dependencies = Jelly::select($related_model)->where($related_model_field->name, '=', $model_id)->execute();

						if ($field instanceof Field_HasManyUniquely) {
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
					} elseif ($related_model_field instanceof Field_ManyToMany && $related_model_field->foreign['model'] == $model_name) {
						$get_links = Jelly::select($related_model_field->through['model'])
								->select($related_model_field->through['columns'][0])
								->where($related_model_field->through['columns'][1], '=', $model_id)
								->execute();

						foreach ($get_links as $link) {
							$related = Jelly::select($related_model, $link->{$related_model_field->through['columns'][0]});
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

		$this->template->content = View::factory(
			'kadmium/delete_dependencies',
			array(
				'page_title' => $page_title,
				'item_type' => $item_type,
				'item_name' => $model->name(),
				'belongs_to' => $belongs_to,
				'children' => $children,
				'edit_link' => Html::anchor(
									$this->request
										->uri(array(
											'action' => 'edit',
										)),
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
		if (Arr::get($_POST, 'my-action') == $page_title) {
			// IsPostBack
			$name = $model->name();
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
					'item_type' => $item_type,
					'item_name' => $model->name()
				)
			);
		}
	}

	/**
	 * @return Jelly_Model
	 */
	protected function get_model($model_name, $id = 0)
	{
		if ($id > 0) {
			$model = Jelly::select($model_name, $id);
			if (!$model->loaded()) {
				$this->page_not_found();
			}
		} else {
			$model = Jelly::factory($model_name);
		}
		return $model;
	}

	protected function generate_fields(Jelly_Model $model, Jelly_Meta $meta, $field_prefix, array $validation_errors = array())
	{
		$fields = array();
		foreach ($meta->fields() as $field_id => $field) {
			$this->generate_field($model, $fields, $field_id, $field, $validation_errors);
		}
		return $fields;
	}

	protected function generate_field(Jelly_Model $model, & $fields, $field_id, $field, array $validation_errors = array())
	{
		$field_id_attr = 'field-' . $field->name;

		if (!$this->include_field($field, $model->id() == 0)) {
			return;
		}

		$id_attribs = array('attributes'=>array('id'=>$field_id_attr));

		if ($field instanceof Field_HasManyUniquely) {
			$label = '<h3>' . $field->label;
			if (isset($field->sort_on)) {
				$label .= ' (drag to sort)';
			}
			$label .= '</h3>';
		} else {
			$label = Form::label($field_id_attr, $field->label);
		}

		if (isset($validation_errors[$field_id])) {
			array_push($field->css_class, 'error');
			$fields[$label] = $model->input($field->name, $id_attribs);
			$fields[$label]->errors = $validation_errors[$field_id];
		} else {
			$fields[$label] = $model->input($field->name, $id_attribs);
		}

	}

	protected function include_field($field, $hide_has_many_uniquely = FALSE)
	{
		if ($field instanceof Field_Primary) {
			return FALSE;
		}
		if ($field->show_in_edit === FALSE) {
			return FALSE;
		}
		if ($field instanceof Field_Timestamp && ($field->auto_now_create || $field->auto_now_update)) {
			return FALSE;
		}
		// We don't update Field_HasManyUniquely fields but we do need to include them when we are layout out the form (as they appear as a series of links to actually update the fields).
		if ($hide_has_many_uniquely && $field instanceof Field_HasManyUniquely) {
			return FALSE;
		}
		return TRUE;
	}

	// override for more specific behaviour
	protected function page_not_found()
	{
		$this->request->status = 404;
		$this->auto_render = false;
	}

}
