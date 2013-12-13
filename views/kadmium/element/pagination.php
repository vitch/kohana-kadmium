<div class="alert">
	<h2><?= $page->total_items(); ?> results <small>viewing <?= $page->current_first_item(); ?>-<?= $page->current_last_item(); ?></small></h2>
</div>

<div class="pagination pagination-centered">

	<ul>

		<?php

			if ($page->previous_page() === FALSE){
				echo '<li class="prev disabled">';
				echo Html::anchor(
					'#',
					'&larr;'
				);
				echo '</li>';
			} else {
				echo '<li class="prev">';
				echo Html::anchor(
					$page->url($page->previous_page()),
					'&larr;'
				);
				echo '</li>';
			}

			// Number of page links in the begin and end of whole range
			$count_out = ( ! empty($config['count_out'])) ? (int) $config['count_out'] : 4;
			// Number of page links on each side of current page
			$count_in = ( ! empty($config['count_in'])) ? (int) $config['count_in'] : 5;

			// Beginning group of pages: $n1...$n2
			$n1 = 1;
			$n2 = min($count_out, $page->total_pages());

			// Ending group of pages: $n7...$n8
			$n7 = max(1, $page->total_pages() - $count_out + 1);
			$n8 = $page->total_pages();

			// Middle group of pages: $n4...$n5
			$n4 = max($n2 + 1, $page->current_page() - $count_in);
			$n5 = min($n7 - 1, $page->current_page() + $count_in);
			$use_middle = ($n5 >= $n4);

			// Point $n3 between $n2 and $n4
			$n3 = (int) (($n2 + $n4) / 2);
			$use_n3 = ($use_middle && (($n4 - $n2) > 1));

			// Point $n6 between $n5 and $n7
			$n6 = (int) (($n5 + $n7) / 2);
			$use_n6 = ($use_middle && (($n7 - $n5) > 1));

			// Links to display as array(page => content)
			$links = array();

			// Generate links data in accordance with calculated numbers
			for ($i = $n1; $i <= $n2; $i++)
			{
				$links[$i] = true;
			}
			if ($use_n3)
			{
				$links[$n3] = false;
			}
			for ($i = $n4; $i <= $n5; $i++)
			{
				$links[$i] = true;
			}
			if ($use_n6)
			{
				$links[$n6] = false;
			}
			for ($i = $n7; $i <= $n8; $i++)
			{
				$links[$i] = true;
			}

			$pages = range(1, $page->total_pages());

			foreach ($links as $i => $is_link) {
				$css_class = '';
				if (!$is_link) {
					$css_class = 'disabled';
				} else if($i === $page->current_page()) {
					$css_class = 'active';
				}
				echo '<li class="' . $css_class . '">';
				if ($is_link) {
					echo Html::anchor(
						$page->url($i),
						($i < 10 ? '&nbsp;' : '') . $i
					);
				} else {
					echo Html::anchor('#', '...');
				}
				
				echo '</li>';
			}


			if ($page->next_page() === FALSE){
				echo '<li class="next disabled">';
				echo Html::anchor(
					'#',
					'&rarr;'
				);
				echo '</li>';
			} else {
				echo '<li class="next">';
				echo Html::anchor(
					$page->url($page->next_page()),
					'&rarr;'
				);
				echo '</li>';
			}
		?>
	</ul>
</div>