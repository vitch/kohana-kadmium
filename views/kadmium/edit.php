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
<?php
if ($show_submit) {
	echo Form::submit('my-action', $save_button_label);
}
if ($delete_link != ''){
	echo $delete_link;
}
?>
</form>

<?php
	if(isset($after_form_content)):
?>
	<div class="after-form"><?= $after_form_content; ?></div>
<?php
	endif;
?>