<div class="row">
	<div class="span16">
		<h1><?= $page_title; ?></h1>

		<?php
		if ($error_message != ''):
		?>
		<div class="alert-message error"><?= $error_message; ?></div>
		<?php
		elseif ($feedback_message != ''):
		?>
		<div class="alert-message success"><?= $feedback_message; ?></div>
		<?php
		endif
		?>

		<form method="post" class="saveableNOT" enctype="multipart/form-data">
			<?= $fields; ?>
			<div class="actions">
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