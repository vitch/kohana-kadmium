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
			<h1><?= $page_title; ?></h1>
		</div>

		<div class="alert alert-block alert-warning">
			<p>
				The <?= $item_type; ?> called "<?= $item_name; ?>" was successfully deleted!
			</p>
		</div>
		<?php
			if ($auto_close) {
		?>
		<script type="text/javascript">
			parent.$.fn.colorbox.close();
		</script>
		<?php
			}
		?>
	</div>
</div>