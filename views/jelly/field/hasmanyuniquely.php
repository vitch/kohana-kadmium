<?php
	$lb_class = isset($field->prevent_lightbox) && $field->prevent_lightbox ? '' : ' lb';
	$ul_classes = array('has-many-uniquely');
	$is_img_list = FALSE;
	if(isset($field->list_as_thumbnails) && $field->list_as_thumbnails) {
		$ul_classes[] = 'img-list';
		$is_img_list = TRUE;
	};
	if(isset($field->sort_on) && $field->sort_on) {
		$ul_classes[] = 'sortable';
	}
?>
<ul class="<?= implode(' ', $ul_classes); ?>" rel="<?= $name; ?>" id="<?= $name; ?>">
	<?php
	foreach($value as $child_model):
	?>
		<li rel="<?= $child_model->id(); ?>">
			<?php
				if ($is_img_list) {
					$image_field = $child_model->meta()->field($field->list_as_thumbnails);
					$path = count($image_field->thumbnails) ? $image_field->thumbnails[0]['path'] : $image_field->path;
					$link_contents = Html::image(
						str_replace(DOCROOT, '', $path) . $child_model->get($field->list_as_thumbnails),
						array(
							'alt' => $child_model->name(),
							'title' => $child_model->name(),
						)
					);
				} else {
					$link_contents = $child_model->name();
				}
				echo '<span>' . $link_contents . '</span>';
				echo Html::anchor(
					Route::get('kadmium_child_edit')->uri(
						array(
							'controller' => Request::current()->controller(),
							'child_action' => 'edit',
							'parent_id' => $model->id(),
							'action' => Jelly::model_name($child_model),
							'id' => $child_model->id()
						)
					),
					'edit',
					array(
						'class' => 'edit' . $lb_class
					)
				)
			?>
		</li>
	<?php
	endforeach;
	?>
</ul>
<ul class="has-many-uniquely" rel="<?= $name; ?>">
	<li>
		<span>
		<?php
			echo View::factory(
				$add_link_view,
				array(
					'model' => $model,
					'field' => $field,
					'value' => $value,
					'lb_class' => $lb_class,
				)
			);
		?>
		</span>
	</li>
</ul>
