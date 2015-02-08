
<table class="zebra-striped list-page">
<?php
	foreach ($items as $item):
		if (!isset($fields)) {
			$fields = $item->meta()->fields();
?>
	<thead>
		<tr>
<?php
			foreach ($fields as $field_id => $field):
				if (!$field->show_in_list):
					continue;
				endif;
?>
			<th><?= $field->label; ?></th>
<?php
			endforeach;
			if ($show_edit || $extra_button_view):
?>
			<th>&nbsp;</th>
<?php
			endif;
?>
		</tr>
	</thead>
	<tbody>
<?php
		}
?>
<tr>
<?php
		foreach ($fields as $field_id => $field):
			if (!$field->show_in_list):
				continue;
			endif;
?>
			<td><?= $field->display($item, $item->get($field_id)); ?></td>
<?php
		endforeach;
		if ($show_edit || $extra_button_view):
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
					'Edit',
					array(
						'class' => 'btn'
					)
				);
			}
		?>
	</td>
<?php
		endif;
?>
</tr>
<?php
	endforeach;
?>
	</tbody>
</table>