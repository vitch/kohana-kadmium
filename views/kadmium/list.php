<h1><?= $page_title; ?></h1>

<?php
if ($items->count() == 0) {
?>
<div class="error">
	<p>
		You don't have any <?= Inflector::plural($item_type); ?> yet.
		<?php
			if ($display_add_links) {
				echo 'You can ' . Html::anchor(
					$add_link,
					'create one',
					array(
						'class' => 'link'
					)
				) . ' now.';
			}
		?>
	</p>
</div>
<?php
} else {
?>
<p>
<?php
	if ($display_add_links) {

		echo Html::anchor(
			$add_link,
			'Add new ' . strtolower($item_type),
			array(
				'class' => 'add',
			)
		);
	}
?>
</p>
<?php
	echo View::factory(
		'kadmium/element/list_table',
		array(
			'items' => $items,
			'allow_sorting' => $allow_sorting,
			'show_edit' => $show_edit,
			'extra_button_view' => $extra_button_view,
		)
	);
	echo $pagination;
}
?>