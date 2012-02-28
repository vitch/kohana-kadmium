<select name="<?php echo $name ?>[]" <?php echo HTML::attributes($attributes + array('id' => 'field-'.$name, 'class' => 'span7')) ?> multiple="multiple">
	<?php foreach(Jelly::query($foreign['model'])->select() as $related): ?>
		<?php if (in_array($related->id(), $ids)): ?>
			<option value="<?php echo $related->id() ?>" selected='selected'><?php echo $related->name()?></option>
		<?php else: ?>
			<option value="<?php echo $related->id() ?>"><?php echo $related->name()?></option>
		<?php endif; ?>
	<?php endforeach; ?>
</select>