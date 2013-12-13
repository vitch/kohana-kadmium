<div class="row">
	<div class="span12">
	<?=
		View::factory(
			'kadmium/element/breadcrumb',
			array(
				'breadcrumb' => isset($breadcrumb) ? $breadcrumb : null,
			)
		);
	?>
	<div class="page-header">
		<?php
		if ($display_add_links) {
			echo Html::anchor(
				$add_link,
				'<i class="icon-plus-sign"></i> Add new ' . strtolower($item_type),
				array(
					'class' => 'btn',
					'style' => 'float: right;'
				)
			);
		}
		?>
		<h1><?= $page_title; ?></h1>
	</div>

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
		echo $pagination_overview;
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
	</div>
</div>