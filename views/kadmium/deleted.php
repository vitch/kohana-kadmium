<h1><?= $page_title; ?></h1>

<div class="alert-message block-message warning">
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