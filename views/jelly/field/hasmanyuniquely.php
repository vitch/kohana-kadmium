<ul class="has-many-uniquely" id="<?= $field->name; ?>">
	<?php
	foreach($value as $child_model):
	?>
		<li><a href="<?= sprintf($field->edit_link_base, $model->id) . $child_model->id; ?>" class="edit lb"><?= $child_model->name(); ?></a></li>
	<?php
	endforeach;
	?>
		<li><a href="<?= sprintf($field->edit_link_base, $model->id); ?>0" class="add lb">Add a new <?= Inflector::humanize(Jelly::model_name($value->current())); ?></a></li>
</ul>
