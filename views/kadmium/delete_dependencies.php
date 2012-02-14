<div class="row">
	<div class="span12">
		<?=
			View::factory(
				'kadmium/element/breadcrumb',
				array(
					'breadcrumb' => $breadcrumb,
				)
			);
		?>
		<div class="page-header">
			<h1><?= $page_title; ?></h1>
		</div>

		<div class="alert alert-block alert-error">
			<p>You cannot delete the <?= $item_type; ?> called "<?= $item_name; ?>" because of the following:</p>
			<ul>
			<?php
				foreach ($belongs_to as $item):
			?>
					<li>It is linked to the <?= $item['model']; ?> called "<?=
						Html::anchor(
							$item['link'],
							$item['name']
						);
					?>"</li>
			<?php
				endforeach;
				foreach ($children as $item):
			?>
					<li>It has the dependant <?= $item['model']; ?> called "<?=
						Html::anchor(
							$item['link'],
							$item['name']
						);
					?>"</li>
			<?php
				endforeach;
			?>
			</ul>
			<div class="alert-actions">
			<?= $edit_link; ?>
			</div>
		</div>
	</div>
</div>