<h1><?= $page_title; ?></h1>

<?php
if ($error_message != ''):
?>
<div class="error"><?= $error_message; ?></div>
<?php
elseif ($feedback_message != ''):
?>
<div class="feedback"><?= $feedback_message; ?></div>
<?php
endif
?>

<form method="post" class="saveableNOT" enctype="multipart/form-data">
	<ul class="fields">
		<?php
		echo $fields;
		?>
	</ul>
<input type="submit" name="my-action" value="<?= $page_title; ?>" />
<?php
if ($delete_label != ''):
?>
	<input type="submit" name="my-action" class="delete" value="<?= $delete_label; ?>" />
<?php
endif;
?>
</form>

<?php
	if(isset($after_form_content)):
?>
	<div class="after-form"><?= $after_form_content; ?></div>
<?php
	endif;
?>