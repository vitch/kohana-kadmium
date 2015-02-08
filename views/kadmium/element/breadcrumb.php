<?php
if (isset($breadcrumb)) {
	echo '<ul class="breadcrumb">';
	foreach($breadcrumb as $href=>$label) {
		if ($href == '#') {
			echo '<li class="active">';
			echo $label;
			echo '</li>';
		} else {
			echo '<li>';
			echo Html::anchor(
				$href,
				$label
			) . ' <span class="divider">/</span>';
			echo '</li>';
		}
	}
	echo '</ul>';
}
 
