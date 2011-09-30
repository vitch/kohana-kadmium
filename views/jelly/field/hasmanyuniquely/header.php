<label><?php
	echo $label;
	if ($is_sortable && $num_items > 1) {
		echo ' (drag to sort)';
	}
?></label>