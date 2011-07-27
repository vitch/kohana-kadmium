<?php
echo Html::anchor(
	Route::get('kadmium_child_edit')->uri(
		array(
			'controller' => Request::current()->controller(),
			'child_action' => 'edit',
			'parent_id' => $model->id(),
			'action' => $field->foreign['model'],
			'id' => 0
		)
	),
	'Add a new ' . Inflector::humanize(Jelly::model_name($value->current())),
	array(
		'class' => 'add' . $lb_class
	)
);