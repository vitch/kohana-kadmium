
<table class="table table-bordered table-striped list-page">
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
					$css_class .= 'is-sorter ';
					if ($d === 1) {
						$css_class .= 'dir-up';
					} else {
						$css_class .= 'dir-down';
					}
				}

				?>
				<th><?php
				if (isset($allow_sorting) && $allow_sorting && $field->is_sortable) {
					echo Html::anchor(
						Request::current()->uri() . '?s=' . $field_id . '&d=' . $d,
						'<i class="icon icon-sort"> </i>' . $field->label,
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

		echo '<tr class="' . $item->list_page_class . '">';

		foreach ($fields as $field_id => $field) {
			if (!$field->show_in_list) {
				continue;
			}
?>
			<td><?= $field->display($item, $item->{$field_id}); ?></td>
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
					$item->get_edit_link(),
					'Edit',
					array(
						'class' => 'btn'
					)
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