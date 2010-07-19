<?php
	$lb_class = isset($field->prevent_lightbox) && $field->prevent_lightbox ? '' : ' lb';
	$ul_class = isset($field->list_as_thumbnails) && $field->list_as_thumbnails ? '' : ' img-list';
?>
<ul class="has-many-uniquely" id="<?= $field->name; ?>">
	<?php
	foreach($value as $child_model):
	?>
		<li>
			<?php
				if ($ul_class == '') {
					$image_field = $child_model->meta()->fields($field->list_as_thumbnails);
					$path = count($image_field->thumbnails) ? $image_field->thumbnails[0]['path'] : $image_field->path;
					$link_contents = Html::image(
						str_replace(DOCROOT, '', $path) . $child_model->get($field->list_as_thumbnails)
					);
				} else {
					$link_contents = $child_model->name();
				}
				echo Html::anchor(
					sprintf($field->edit_link_base, $model->id()) . $child_model->id(),
					$link_contents,
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
