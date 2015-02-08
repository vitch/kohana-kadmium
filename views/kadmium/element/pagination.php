<div class="pagination">
	<ul>

		<?php

			if ($page->previous_page() === FALSE){
				echo '<li class="prev disabled">';
				echo Html::anchor(
					'#',
					'&larr; Previous'
				);
				echo '</li>';
			} else {
				echo '<li class="prev">';
				echo Html::anchor(
					$page->url($page->previous_page()),
					'&larr; Previous'
				);
				echo '</li>';
			}

			for ($i = 1; $i <= $page->total_pages(); $i++) {
				echo '<li' . ($i == $page->current_page() ? ' class="active"' : '') . '>';
				echo Html::anchor(
					$page->url($i),
					$i
				);
				echo '</li>';
			}


			if ($page->next_page() === FALSE){
				echo '<li class="next disabled">';
				echo Html::anchor(
					'#',
					'Next &rarr;'
				);
				echo '</li>';
			} else {
				echo '<li class="next">';
				echo Html::anchor(
					$page->url($page->next_page()),
					'Next &rarr;'
				);
				echo '</li>';
			}
		?>
	</ul>
</div>