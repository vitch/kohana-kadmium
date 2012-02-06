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

		<div class="alert alert-block alert-warning">
			<p>You cannot delete the <?= $item_type; ?> called "<?= $item_name; ?>"?</p>
			<div class="alert-actions">
				<?= $edit_link; ?>
			</div>
		</div>


	</div>
</div>