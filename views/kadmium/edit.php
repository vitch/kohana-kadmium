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

		<?php
		if ($error_message != ''):
		?>
		<div class="alert alert-block alert-error"><?= $error_message; ?></div>
		<?php
		elseif ($feedback_message != ''):
		?>
		<div class="alert alert-block alert-success"><?= $feedback_message; ?></div>
		<?php
		endif
		?>

		<form method="post" class="saveableNOT form-horizontal" enctype="multipart/form-data">
			<?= $fields; ?>
			<div class="form-actions">
				<?php
					echo implode(' ', $action_buttons);
				?>
			</div>
		</form>

		<?php
			if(isset($after_form_content)) {
		?>
			<div class="after-form"><?= $after_form_content; ?></div>
		<?php
			}
		?>
	</div>
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