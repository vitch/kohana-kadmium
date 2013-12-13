<div class="pagination">

	<ul>

		<?php

			$links = array();

			// Previous link
			$links[] = array(
				'class' => 'prev' . ($page->previous_page() === FALSE ? ' disabled' : ''),
				'link' => $page->previous_page() === FALSE ? '#' : $page->url($page->previous_page()),
				'content' => '&larr;',
			);

			// Calculate which page links to display
			$max_pages_to_display = (!empty($config['max_pages_to_display'])) ? (int) $config['max_pages_to_display'] : 18;

			$num_pages = $page->total_pages();
			$first_page = 1;
			$current_page = $page->current_page();
			$last_page = $num_pages;
			if ($num_pages > $max_pages_to_display) {
				$first_page = $current_page - floor($max_pages_to_display / 2);
				$last_page = $current_page + floor($max_pages_to_display / 2);
				while($first_page < 1) {
					$first_page++;
					$last_page ++;
				}
				while($last_page > $num_pages) {
					$first_page--;
					$last_page--;
				}
			}

			// Create link items for each page
			foreach(range($first_page, $last_page) as $i) {
				$links[] = array(
					'class' => $i == $current_page ? 'active' : '',
					'link' => $page->url($i),
					'content' => ($i < 10 ? '&nbsp;' : '') . $i
				);
			}

			// Next link
			$links[] = array(
				'class' => 'next' . ($page->next_page() === FALSE ? ' disabled' : ''),
				'link' => $page->next_page() === FALSE ? '#' : $page->url($page->next_page()),
				'content' => '&rarr;',
			);

			// Render pages
			foreach ($links as $link) {
				echo '<li class="' . $link['class'] . '">';
				echo Html::anchor(
					$link['link'],
					$link['content']
				);
				echo '</li>';
			}
		?>
	</ul>
</div>