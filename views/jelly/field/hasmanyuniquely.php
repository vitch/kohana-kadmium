<ul class="has-many-uniquely" id="<?= $field->name; ?>">
	<?php
	foreach($value as $child_model):
		$lb_class = isset($field->prevent_lightbox) && $field->prevent_lightbox ? '' : ' lb';
	?>
		<li><a href="<?= sprintf($field->edit_link_base, $model->id()) . $child_model->id(); ?>" class="edit<?= $lb_class; ?>"><?= $child_model->name(); ?></a></li>
	<?php
	endforeach;
	?>
		<li><a href="<?= sprintf($field->edit_link_base, $model->id()); ?>0" class="add<?= $lb_class; ?>">Add a new <?= Inflector::humanize(Jelly::model_name($value->current())); ?></a></li>
</ul>
