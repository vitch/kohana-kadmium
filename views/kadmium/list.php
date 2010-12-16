<h1><?= $page_title; ?></h1>

<?php
if ($items->count() == 0) :
?>
<div class="error">
	<p>
		You don't have any <?= Inflector::plural($item_type); ?> yet. You can <?=
			Html::anchor(
				Route::get('kadmium')
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
<?php
	echo View::factory(
		'kadmium/element/list_table',
		array(
			'items' => $items,
			'show_edit' => $show_edit,
			'extra_button_view' => $extra_button_view,
		)
	);
	echo $pagination;
endif;
?>