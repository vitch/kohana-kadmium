<ul class="pagination">

	<?php if ($previous_page !== FALSE): ?>
		<li class="link-back"><a href="<?php echo $page->url($previous_page) ?>">prev</a></li>
	<?php endif ?>

	<?php for ($i = 1; $i <= $total_pages; $i++): ?>
		<li<?php if ($i == $current_page):
			echo ' class="selected"';
		endif;
		?>>
			<a href="<?php echo $page->url($i) ?>"><?php echo $i ?></a>
		</li>
	<?php endfor ?>

	<?php if ($next_page !== FALSE): ?>
		<li class="link"><a href="<?php echo $page->url($next_page) ?>">next</a></li>
	<?php endif ?>


</ul><!-- .pagination -->