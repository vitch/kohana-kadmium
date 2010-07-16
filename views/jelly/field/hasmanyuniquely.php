<ul class="has-many-uniquely" id="<?= $field->name; ?>">
	<?php
	foreach($value as $child_model):
		$lb_class = isset($field->prevent_lightbox) && $field->prevent_lightbox ? '' : ' lb';
	?>
		<li>
			<?php
				echo Html::anchor(
					sprintf($field->edit_link_base, $model->id()) . $child_model->id(),
					$child_model->name(),
					array(
						'class' => 'edit' . $lb_class
					)
				)
			?>
		</li>
	<?php
	endforeach;
	?>
		<li>
			<?php
				echo Html::anchor(
					sprintf($field->edit_link_base, $model->id()) . 0,
					'Add a new ' . Inflector::humanize(Jelly::model_name($value->current())),
					array(
						'class' => 'add' . $lb_class
					)
				)
			?>
		</li>
</ul>
