
<table class="list-page">
<?php
	foreach ($items as $item) {
		if (!isset($fields)) {
			$fields = $item->meta()->fields();
			$current_sort_on = Arr::get($_GET, 's');
			$current_direction = Arr::get($_GET, 'd', 1);
?>
	<thead>
		<tr>
<?php
			foreach ($fields as $field_id => $field) {
				if (!$field->show_in_list) {
					continue;
				}
				$d = $current_direction;
				$css_class = '';
				if ($field_id == $current_sort_on) {
					$d *= -1;
					$css_class .= 'is-sorter';
					if ($d == -1) {
						$css_class .= ' desc';
					}
				}
?>
			<th><?php
				if (isset($allow_sorting) && $allow_sorting) {
					echo Html::anchor(
						Request::current()->uri() . '?s=' . $field_id . '&d=' . $d,
						$field->label,
						array(
							'class' => $css_class
						)
					);
				} else {
					echo $field->label;
				}
			?></th>
<?php
			}
			if ($show_edit || $extra_button_view) {
?>
			<th>&nbsp;</th>
<?php
			}
?>
		</tr>
	</thead>
	<tbody>
<?php
		}
?>
<tr>
<?php
		foreach ($fields as $field_id => $field) {
			if (!$field->show_in_list) {
				continue;
			}
?>
			<td><?= $field->display($item, $item->get($field_id)); ?></td>
<?php
		}
		if ($show_edit || $extra_button_view) {
?>
	<td>
		<?php
			if ($extra_button_view) {
				echo View::factory(
					$extra_button_view,
					array(
						'item' => $item,
					)
				);
			}
			if ($show_edit) {
				echo Html::anchor(
					Route::get('kadmium')
						->uri(array(
							'controller' => Request::current()->controller(),
							'action' => 'edit',
							'id' => $item->id(),
						)),
					'Edit'
				);
			}
		?>
	</td>
<?php
		}
?>
</tr>
<?php
	}
?>
	</tbody>
</table>