<script type="text/javascript">
	if (window.autocompleteOptions == undefined) {
		window.autocompleteOptions = {};
	}
	<?php
		$options = array();
		foreach(Jelly::query($foreign['model'])->select() as $related) {
			$options[$related->id()] = $related->name();
		}
		echo 'window.autocompleteOptions[\'options-' . $name . '\'] = ' . json_encode($options);
	?>
</script>
<?=
	Form::input(
		$name,
		implode(',', $ids),
		$attributes + 
		array(
			'type' => 'hidden'
		)
	)
?>