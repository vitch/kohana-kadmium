<fieldset id="<?= $field_id; ?>">
	<legend><?= $label; ?></legend>
	<?=
		View::factory(
			'kadmium/fields',
			array(
				'fields' => $fields
			)
		);
	?>
</fieldset>