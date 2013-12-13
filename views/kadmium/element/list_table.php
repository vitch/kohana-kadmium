
<table class="list-page">
<?php
	foreach ($items as $item) {
		if (!isset($fields)) {
			$fields = $item->meta()->fields();
			$get_params = Arr::merge(array('d'=>'1'), $_GET);
?>
	<thead>
		<tr>
<?php
			foreach ($fields as $field_id => $field) {
				if (!$field->show_in_list) {
					continue;
				}
				$get_params['s'] = $field_id;
				$querystring = http_build_query($get_params);
				$css_class = '';
				if ($field_id == Arr::get($_GET, 's')) {
					$get_params['d'] *= -1;
					$querystring = http_build_query($get_params);
					$css_class .= 'is-sorter ';
					if ($get_params['d'] === 1) {
						// $css_class .= 'dir-up';
					} else {
						$css_class .= 'desc';
					}
					$get_params['d'] *= -1;
				}
?>
			<th><?php
				if (isset($allow_sorting) && $allow_sorting) {
					echo Html::anchor(
						Request::current()->uri() . '?' . $querystring,
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