<h1><?= $page_title; ?></h1>

<?php
if ($items->count() == 0) :
?>
<div class="error">
	<p>
		You don't have any <?= Inflector::plural($item_type); ?> yet. You can <?=
			Html::anchor(
						Route::get('admin')
							->uri(array(
								'controller' => Request::instance()->controller,
								'action' => 'new'
							)),
						'create one',
						array(
							'class' => 'link'
						)
					);
		?> now.
	</p>
</div>
<?php
else:
?>
<p>
	<?= $add_link; ?>
</p>
<table class="list-page">
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
			if ($show_edit):
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
		if ($show_edit):
?>
	<td>
		<?=
			Html::anchor(
				Route::get('admin')
					->uri(array(
						'controller' => Request::instance()->controller,
						'action' => 'edit',
						'id' => $item->id(),
					)),
				'Edit'
			);
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

<?php
	echo $pagination;
endif;
?>