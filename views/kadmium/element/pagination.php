<ul class="pagination">

	<?php if ($page->previous_page() !== FALSE): ?>
		<li class="link-back"><a href="<?php echo $page->url($page->previous_page()) ?>">prev</a></li>
	<?php endif ?>

	<?php for ($i = 1; $i <= $page->total_pages(); $i++): ?>
		<li<?php if ($i == $page->current_page()):
			echo ' class="selected"';
		endif;
		?>>
			<a href="<?php echo $page->url($i) ?>"><?php echo $i ?></a>
		</li>
	<?php endfor ?>

	<?php if ($page->next_page() !== FALSE): ?>
		<li class="link"><a href="<?php echo $page->url($page->next_page()) ?>">next</a></li>
	<?php endif ?>


</ul><!-- .pagination -->